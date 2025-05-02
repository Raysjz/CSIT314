<?php
namespace Csit314\CleaningPlatform;

require_once(__DIR__ . '/ConnectiontoDB.php');

class HomeownerCleaningService {
    protected $serviceId;
    protected $categoryId;
    protected $categoryName;
    protected $title;
    protected $description;
    protected $price;
    protected $availability;
    protected $createdAt;
    protected $updatedAt;

    public function __construct($serviceId, $categoryId, $categoryName, $title, $description, $price, $availability, $createdAt = null, $updatedAt = null) {
        $this->serviceId = $serviceId;
        $this->categoryId = $categoryId;
        $this->categoryName = $categoryName;
        $this->title = $title;
        $this->description = $description;
        $this->price = $price;
        $this->availability = $availability;
        $this->createdAt = $createdAt;
        $this->updatedAt = $updatedAt;
    }

    // Getters...
    public function getServiceId() { return $this->serviceId; }
    public function getCategoryId() { return $this->categoryId; }
    public function getCategoryName() { return $this->categoryName; }
    public function getTitle() { return $this->title; }
    public function getDescription() { return $this->description; }
    public function getPrice() { return $this->price; }
    public function getAvailability() { return $this->availability; }
    public function getCreatedAt() { return $this->createdAt; }
    public function getUpdatedAt() { return $this->updatedAt; }

    // Views all Cleaning Services   
    public static function viewHOCleaningServices() {
        $db = Database::getPDO();
        $sql = "SELECT cs.* , sc.category_name
                FROM cleaner_services cs
                JOIN service_categories sc ON cs.category_id = sc.category_id
                WHERE cs.is_suspended = false
                ORDER BY cs.service_id ASC ";
        $stmt = $db->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
        $cleaningServices = [];
        foreach ($result as $row) {
            $cleaningServices[] = new HomeownerCleaningService(
                $row['service_id'],
                $row['category_id'],
                $row['category_name'],
                $row['title'],
                $row['description'],
                $row['price'],
                $row['availability'],
                $row['created_at'],
                $row['updated_at']
            );
        }
        return $cleaningServices;
    }
    
    // Search Cleaning Services   
    public static function searchHOCleaningServices($searchQuery) {
        $db = Database::getPDO();
    
        // Prepare the search string for partial matching
        $searchLike = "%" . $searchQuery . "%";
    
        // Only show not suspended services
        $sql = "SELECT cs.* , sc.category_name
                FROM cleaner_services cs
                JOIN service_categories sc ON cs.category_id = sc.category_id
                WHERE cs.is_suspended = false
                  AND (title ILIKE :search OR service_id::text ILIKE :search)";
        $params = [':search' => $searchLike];
    
        $stmt = $db->prepare($sql);
        $stmt->execute($params);
    
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $cleaningServices = [];
    
        foreach ($result as $row) {
            $cleaningServices[] = new HomeownerCleaningService(
                $row['service_id'],
                $row['category_id'],
                $row['category_name'],
                $row['title'],
                $row['description'],
                $row['price'],
                $row['availability'],
                $row['created_at'],
                $row['updated_at']
            );
        }
        return $cleaningServices;
    }
    
    public static function getCleaningServiceById($serviceId) {
        $db = Database::getPDO();
        $sql = "SELECT cs.*, sc.category_name
                FROM cleaner_services cs
                JOIN service_categories sc ON cs.category_id = sc.category_id
                WHERE cs.service_id = :service_id AND cs.is_suspended = false";
        $stmt = $db->prepare($sql);
        $stmt->execute([':service_id' => $serviceId]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
    
        if ($row) {
            return new HomeownerCleaningService(
                $row['service_id'],
                $row['category_id'],
                $row['category_name'],
                $row['title'],
                $row['description'],
                $row['price'],
                $row['availability'],
                $row['created_at'],
                $row['updated_at'],
                $row['cleaner_account_id'] // Add this if you want to fetch contact info
            );
        } else {
            return null;
        }
    }

    
}
?>