<?php
require_once(__DIR__ . '/ConnectiontoDB.php');

class CleaningService {
    protected $serviceId;
    protected $cleanerAccountId;
    protected $categoryId;
    protected $title;
    protected $description;
    protected $price;
    protected $availability;
    protected $isSuspended;
    protected $createdAt;
    protected $updatedAt;
    protected $categoryName;

    // Constructor
    public function __construct($serviceId, $cleanerAccountId, $categoryId, $title, $description, $price, $availability, $isSuspended, $createdAt = null, $updatedAt = null,$categoryName = null) {
        $this->serviceId = $serviceId;
        $this->cleanerAccountId = $cleanerAccountId;
        $this->categoryId = $categoryId;
        $this->title = $title;
        $this->description = $description;
        $this->price = $price;
        $this->availability = $availability;
        $this->isSuspended = $isSuspended;
        $this->createdAt = $createdAt;
        $this->updatedAt = $updatedAt;
        $this->categoryName = $categoryName;
    }

    // Example getter methods
    public function getServiceId() { return $this->serviceId; }
    public function getCleanerAccountId() { return $this->cleanerAccountId; }
    public function getCategoryId() { return $this->categoryId; }
    public function getTitle() { return $this->title; }
    public function getDescription() { return $this->description; }
    public function getPrice() { return $this->price; }
    public function getAvailability() { return $this->availability; }
    public function isSuspended() { return $this->isSuspended; }
    public function getCreatedAt() { return $this->createdAt; }
    public function getUpdatedAt() { return $this->updatedAt; }
    public function getCategoryName() {return $this->categoryName; }


    
    // Validate user input data
    public function validateCleaningService() {
        // Check for required fields
        if (empty($this->title) || empty($this->description) || empty($this->price) || empty($this->availability)) {
            return "All fields are required.";
        }
    
        // Check that price is a positive number
        if (!is_numeric($this->price) || $this->price < 0) {
            return "Price must be a positive number.";
        }
    
        $db = Database::getPDO();
    
        // Optional: Check if a service with the same title already exists for this cleaner
        $stmt = $db->prepare("SELECT * FROM cleaner_services WHERE title = :title AND cleaner_account_id = :cleanerId");
        $stmt->bindParam(':title', $this->title);
        $stmt->bindParam(':cleanerId', $this->cleanerAccountId, PDO::PARAM_INT);
        $stmt->execute();
        if ($stmt->rowCount() > 0) {
            return "You already have a service with this title.";
        }
    
        return "Validation passed.";
    }

    public function saveCleaningService() {
        $db = Database::getPDO();
        
        $stmt = $db->prepare("INSERT INTO cleaner_services 
            (cleaner_account_id, category_id, title, description, price, availability, is_suspended) 
            VALUES (:cleaner_account_id, :category_id, :title, :description, :price, :availability, :is_suspended)");
    
        $stmt->bindParam(':cleaner_account_id', $this->cleanerAccountId, PDO::PARAM_INT);
        $stmt->bindParam(':category_id', $this->categoryId, PDO::PARAM_INT);
        $stmt->bindParam(':title', $this->title);
        $stmt->bindParam(':description', $this->description);
        $stmt->bindParam(':price', $this->price);
        $stmt->bindParam(':availability', $this->availability);
        $stmt->bindParam(':is_suspended', $this->isSuspended, PDO::PARAM_BOOL);
    
        return $stmt->execute();
    }
    

    // Views all Cleaning Services   
    public static function viewCleaningServices($accountId = null) {
        $db = Database::getPDO();
    
        $sql = "SELECT cs.*, sc.category_name
        FROM cleaner_services cs
        JOIN service_categories sc ON cs.category_id = sc.category_id";

        if ($accountId !== null) {
            $sql .= " WHERE cs.cleaner_account_id = :account_id";
        }

        $sql .= " ORDER BY cs.service_id ASC";
    
        $stmt = $db->prepare($sql);
    
        if ($accountId !== null) {
            $stmt->execute([':account_id' => $accountId]);
        } else {
            $stmt->execute();
        }
    
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $cleaningServices = [];
    
        foreach ($result as $row) {
            $cleaningServices[] = new CleaningService(
                $row['service_id'],
                $row['cleaner_account_id'],
                $row['category_id'],
                $row['title'],
                $row['description'],
                $row['price'],
                $row['availability'],
                isset($row['is_suspended']) ? (bool)$row['is_suspended'] : false,
                $row['created_at'],
                $row['updated_at'],
                $row['category_name'] // <-- Add this
            );
        }

        // Return the array of CleaningService objects
        return $cleaningServices;
    }
    
   
    
        public static function searchCleaningServices($searchQuery, $accountId = null) {
            $db = Database::getPDO();
        
            // Prepare the search string for partial matching
            $searchLike = "%" . $searchQuery . "%";
        
            // Build the SQL query
            $sql = "SELECT * FROM cleaner_services WHERE (title ILIKE :search OR service_id::text ILIKE :search)";
            $params = [':search' => $searchLike];
        
            // If account ID is provided, filter by cleaner_account_id as well
            if ($accountId !== null) {
                $sql .= " AND cleaner_account_id = :accountId";
                $params[':accountId'] = $accountId;
            }
        
            $stmt = $db->prepare($sql);
            $stmt->execute($params);
        
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $cleaningServices = [];
        
            foreach ($result as $row) {
                $cleaningServices[] = new CleaningService(
                    $row['service_id'],
                    $row['cleaner_account_id'],
                    $row['category_id'],
                    $row['title'],
                    $row['description'],
                    $row['price'],
                    $row['availability'],
                    $row['is_suspended'],
                    $row['created_at'],
                    $row['updated_at']
                );
            }
            return $cleaningServices;
        }
        
    
        public static function getCleaningServiceById($id) {
            $db = Database::getPDO();
            
            $stmt = $db->prepare("SELECT * FROM cleaner_services WHERE service_id = :id");
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
        
            $service = $stmt->fetch(PDO::FETCH_ASSOC);
        
            if ($service) {
                return new CleaningService(
                    $service['service_id'],
                    $service['cleaner_account_id'],
                    $service['category_id'],
                    $service['title'],
                    $service['description'],
                    $service['price'],
                    $service['availability'],
                    $service['is_suspended'],
                    $service['created_at'],
                    $service['updated_at']
                );
            } else {
                // Debugging: If no service is found
                echo "No service found with service_id: " . htmlspecialchars($id) . "<br>";
                return null;
            }
        }
        
    

        public function updateCleaningService() {
            $db = Database::getPDO();
        
            $stmt = $db->prepare("UPDATE cleaner_services 
                SET 
                    title = :title,
                    description = :description,
                    price = :price,
                    availability = :availability,
                    is_suspended = :isSuspended,
                    category_id = :categoryId,
                    updated_at = NOW()
                WHERE service_id = :id");
        
            $stmt->bindParam(':id', $this->serviceId, PDO::PARAM_INT);
            $stmt->bindParam(':title', $this->title);
            $stmt->bindParam(':description', $this->description);
            $stmt->bindParam(':price', $this->price);
            $stmt->bindParam(':availability', $this->availability);
            $stmt->bindParam(':isSuspended', $this->isSuspended, PDO::PARAM_BOOL);
            $stmt->bindParam(':categoryId', $this->categoryId, PDO::PARAM_INT);
        
            return $stmt->execute();
        }
        

        public function suspendCleaningService() {
            $db = Database::getPDO();
        
            $stmt = $db->prepare("UPDATE cleaner_services SET is_suspended = true WHERE service_id = :id");
            $stmt->bindParam(':id', $this->serviceId, PDO::PARAM_INT);
        
            return $stmt->execute();
        }

        public static function getCleanerByServiceId($serviceId) {
            $db = Database::getPDO();
            $sql = "SELECT u.*
                    FROM cleaner_services cs
                    JOIN user_accounts u ON cs.cleaner_account_id = u.account_id
                    WHERE cs.service_id = :service_id";
            $stmt = $db->prepare($sql);
            $stmt->execute([':service_id' => $serviceId]);
            return $stmt->fetch(PDO::FETCH_OBJ);
        }
        
        
        
        public static function getServicesByCleaner($cleanerId) {
            $db = Database::getPDO();
            $sql = "SELECT cs.*, sc.category_name
                    FROM cleaner_services cs
                    JOIN service_categories sc ON cs.category_id = sc.category_id
                    WHERE cs.cleaner_account_id = :cleaner_id
                    ORDER BY cs.service_id ASC";
            $stmt = $db->prepare($sql);
            $stmt->execute([':cleaner_id' => $cleanerId]);
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
            $services = [];
            foreach ($result as $row) {
                $services[] = new CleaningService(
                    $row['service_id'],
                    $row['cleaner_account_id'],
                    $row['category_id'],
                    $row['title'],
                    $row['description'],
                    $row['price'],
                    $row['availability'],
                    $row['is_suspended'],
                    $row['created_at'],
                    $row['updated_at'],
                    $row['category_name']
                );
            }
            return $services;
        }

        //---------Platform Generate Report-----------------
        public static function countCreatedDaily() {
            $db = Database::getPDO();
            $sql = "SELECT COUNT(*) AS new_services_created
                    FROM cleaner_services
                    WHERE created_at::date = CURRENT_DATE";
            $stmt = $db->query($sql);
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            return $row ? (int)$row['new_services_created'] : 0;
        }
    
        public static function countCreatedWeekly() {
            $db = Database::getPDO();
            $sql = "SELECT COUNT(*) AS new_services_created
                    FROM cleaner_services
                    WHERE created_at >= CURRENT_DATE - INTERVAL '6 days'";
            $stmt = $db->query($sql);
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            return $row ? (int)$row['new_services_created'] : 0;
        }
    
        public static function countCreatedMonthly() {
            $db = Database::getPDO();
            $sql = "SELECT COUNT(*) AS new_services_created
                    FROM cleaner_services
                    WHERE DATE_TRUNC('month', created_at) = DATE_TRUNC('month', CURRENT_DATE)";
            $stmt = $db->query($sql);
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            return $row ? (int)$row['new_services_created'] : 0;
        }
        

}
