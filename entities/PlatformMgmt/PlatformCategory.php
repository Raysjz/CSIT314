<?php
require_once(__DIR__ . '/../../entities/ConnectiontoDB.php');

/**
 * Entity class representing a platform service category.
 * Handles all data access and business logic for categories.
 */
class PlatformCategory {
    protected $id;
    protected $name;
    protected $isSuspended;

    public function __construct($id, $name, $isSuspended) {
        $this->id = $id;
        $this->name = $name;
        $this->isSuspended = $isSuspended;
    }

    // --- Getters ---
    public function getId() { return $this->id; }
    public function getName() { return $this->name; }
    public function getIsSuspended() { return $this->isSuspended; }

    /**
     * Fetch all service categories as objects.
     * return PlatformCategory[]
     */
    public static function viewPlatformCategory() {
        $db = Database::getPDO();
        $stmt = $db->prepare("SELECT * FROM service_categories ORDER BY category_id ASC");
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $serviceCategories = [];
        foreach ($result as $row) {
            $serviceCategories[] = new PlatformCategory(
                $row['category_id'],
                $row['category_name'],
                isset($row['is_suspended']) ? (bool)$row['is_suspended'] : false
            );
        }
        return $serviceCategories;
    }

    /**
     * Search categories by ID or name.
     * return PlatformCategory[]
     */
    public static function searchPlatformCategory($searchQuery) {
        $db = Database::getPDO();
        if (is_numeric($searchQuery)) {
            $stmt = $db->prepare("SELECT * FROM service_categories WHERE category_id = :search");
            $stmt->bindValue(':search', (int)$searchQuery, PDO::PARAM_INT);
        } else {
            $stmt = $db->prepare("SELECT * FROM service_categories WHERE LOWER(category_name) LIKE :search");
            $searchTerm = '%' . strtolower($searchQuery) . '%';
            $stmt->bindValue(':search', $searchTerm, PDO::PARAM_STR);
        }
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $serviceCategories = [];
        foreach ($result as $row) {
            $serviceCategories[] = new PlatformCategory(
                $row['category_id'],
                $row['category_name'],
                isset($row['is_suspended']) ? (bool)$row['is_suspended'] : false
            );
        }
        return $serviceCategories;
    }

    /**
     * Validate input before creating/updating a category.
     * return string
     */
    public function validatePC() {
        if (empty($this->name)) {
            return "All fields are required.";
        }
        $db = Database::getPDO();
        $stmt = $db->prepare("SELECT * FROM service_categories WHERE category_name = :name");
        $stmt->bindParam(':name', $this->name);
        $stmt->execute();
        if ($stmt->rowCount() > 0) {
            return "Category Name already exists.";
        }
        return "Validation passed.";
    }

    /**
     * Save a new category to the database.
     * return bool
     */
    public function savePlatformCategory() {
        $db = Database::getPDO();
        $stmt = $db->prepare("INSERT INTO service_categories (category_name, is_suspended) VALUES (:name, :isSuspended)");
        $stmt->bindParam(':name', $this->name);
        $stmt->bindParam(':isSuspended', $this->isSuspended, PDO::PARAM_BOOL);
        return $stmt->execute();
    }

    /**
     * Fetch a category by its ID.
     * param int $id
     * return PlatformCategory|null
     */
    public static function getPlatformCategoryById($id) {
        $db = Database::getPDO();
        $stmt = $db->prepare("SELECT * FROM service_categories WHERE category_id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($result) {
            return new PlatformCategory(
                $result['category_id'],
                $result['category_name'],
                isset($result['is_suspended']) ? (bool)$result['is_suspended'] : false
            );
        }
        return null;
    }

    /**
     * Fetch all active (not suspended) categories as associative arrays.
     * return array
     */
    public static function getAllCategories() {
        $db = Database::getPDO();
        $stmt = $db->query("SELECT category_id, category_name FROM service_categories WHERE is_suspended = false ORDER BY category_name ASC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Update this category in the database.
     * return bool
     */
    public function updatePlatformCategory() {
        $db = Database::getPDO();
        $stmt = $db->prepare("UPDATE service_categories SET category_name = :name, is_suspended = :isSuspended WHERE category_id = :id");
        $stmt->bindParam(':id', $this->id, PDO::PARAM_INT);
        $stmt->bindParam(':name', $this->name);
        $stmt->bindParam(':isSuspended', $this->isSuspended, PDO::PARAM_BOOL);
        return $stmt->execute();
    }

    /**
     * Suspend this category (set is_suspended to true).
     * return bool
     */
    public function suspendPlatformCategory() {
        $db = Database::getPDO();
        $stmt = $db->prepare("UPDATE service_categories SET is_suspended = true WHERE category_id = :id");
        $stmt->bindParam(':id', $this->id, PDO::PARAM_INT);
        return $stmt->execute();
    }
}
?>
