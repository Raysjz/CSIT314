<?php
require_once(__DIR__ . '/ConnectiontoDB.php');

class PlatformCategory{
    protected $id;
    protected $name;
    protected $isSuspended;

    public function __construct($id , $name , $isSuspended) {
        $this->id = $id;
        $this->name = $name;
        $this->isSuspended = $isSuspended;
    }

    // Getter methods
    public function getId() {
        return $this->id;
    }

    public function getName() { 
        return $this->name;
    }

    public function getIsSuspended() {
        return $this->isSuspended;
    }
    

    // Fetch all service categories
    public function viewPlatformCategory() {
        $db = Database::getPDO();

        // Prepare the SQL statement to fetch service categories
        $stmt = $db->prepare("SELECT * FROM service_categories order by category_ID ASC"); 
        $stmt->execute();

        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Initialize an empty array to hold the svcCategory objects
        $serviceCategories = [];
        // Iterate through the results and create a svcCategoryobject for each row
        foreach ($result as $row) {
            $serviceCategories[] = new PlatformCategory(
                $row['category_id'],
                $row['category_name'],
                isset($row['is_suspended']) ? (bool)$row['is_suspended'] : false
            );
        }
    
        // Return the array of Category objects
        return $serviceCategories;
    }

    // Search services by username or id
    public static function searchPlatformCategory($searchQuery) {
        $db = Database::getPDO();

        if (is_numeric($searchQuery)) {
            $stmt = $db->prepare("SELECT * FROM service_categories WHERE category_id = :search");
        } else {
            $stmt = $db->prepare("SELECT * FROM service_categories WHERE category_name LIKE :search");
            $searchQuery = "%" . $searchQuery . "%";
        }

        $stmt->bindParam(':search', $searchQuery);
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

    // Validate user input data
    public function validateSC() {
        if (empty($this->name)) {
            return "All fields are required.";
        }

        $db = Database::getPDO(); 

        // Check if Category Name already exists
        $stmt = $db->prepare("SELECT * FROM service_categories WHERE category_name = :name");
        $stmt->bindParam(':name', $this->name);
        $stmt->execute();
        if ($stmt->rowCount() > 0) {
             return "Category Name already exists.";
         }


        return "Validation passed.";
    }


    public function savePlatformCategory() {
        $db = Database::getPDO();
        
        $stmt = $db->prepare("INSERT INTO service_categories (category_name, is_suspended) 
                              VALUES (:name, :isSuspended)");
    
        $stmt->bindParam(':name', $this->name);
        $stmt->bindParam(':isSuspended', $this->isSuspended, PDO::PARAM_BOOL);
    
        return $stmt->execute();
    }   

    // Fetch user by ID
    public static function getPlatformCategoryById($id) {
        $db = Database::getPDO();
        $stmt = $db->prepare("SELECT * FROM service_categories WHERE category_id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
    
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
    
        if ($user) {
            return new PlatformCategory(
                $user['category_id'],
                $user['category_name'],
                isset($user['is_suspended']) ? (bool)$user['is_suspended'] : false
            );
        } else {
            // Debugging: If no user is found
            echo "No user found with category_id: " . htmlspecialchars($id) . "<br>";
            return null;
        }
    }
    

    // Fetch All 
    public static function getAllCategories() {
        $db = Database::getPDO();
        $stmt = $db->query("SELECT category_id, category_name FROM service_categories ORDER BY category_name ASC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    
    

    // Update user category (method to handle update in the database)
    public function updatePlatformCategory() {
        $db = Database::getPDO();

        $stmt = $db->prepare("UPDATE service_categories SET category_name = :name, is_suspended = :isSuspended WHERE category_id = :id");
        $stmt->bindParam(':id', $this->id, PDO::PARAM_INT);
        $stmt->bindParam(':name', $this->name);
        $stmt->bindParam(':isSuspended', $this->isSuspended, PDO::PARAM_BOOL);

        return $stmt->execute(); // Return whether the update was successful
    }

    // Suspend a user (set is_suspended to true)
    public function suspendPlatformCategory() {
        $db = Database::getPDO();
    
        // Ensure the SQL query uses the correct placeholder :id
        $stmt = $db->prepare("UPDATE service_categories SET is_suspended = true WHERE category_id = :id");
    
        // Use :id in bindParam instead of :category_id
        $stmt->bindParam(':id', $this->id, PDO::PARAM_INT);  // Correctly binding the category ID
    
        return $stmt->execute();  // Execute the query
    }
}
?>
