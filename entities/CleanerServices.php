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

    // Constructor
    public function __construct($serviceId, $cleanerAccountId, $categoryId, $title, $description, $price, $availability, $isSuspended, $createdAt = null, $updatedAt = null) {
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

    // You can add setter methods and business logic as needed


    /*
    // Validate user input data
    public function validateUP() {
        if (empty($this->name)) {
            return "All fields are required.";
        }

        $db = Database::getPDO(); 

        // Check if Profile Name already exists
        $stmt = $db->prepare("SELECT * FROM user_profiles WHERE profile_name = :name");
        $stmt->bindParam(':name', $this->name);
        $stmt->execute();
        if ($stmt->rowCount() > 0) {
             return "Profile Name already exists.";
         }


        return "Validation passed.";
    }

    public static function getProfiles() {
        $db = Database::getPDO();

        // Prepare the SQL statement to fetch all profiles
        $stmt = $db->prepare("SELECT profile_id, profile_name FROM user_profiles");
        $stmt->execute();

        // Fetch all profiles as an associative array
        $profiles = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $profiles;
    }

    
    public static function getProfileIdByName($profileName) {
        $db = Database::getPDO();
        $stmt = $db->prepare("SELECT profile_id FROM user_profiles WHERE name = :name");
        $stmt->bindParam(':name', $profileName);
        $stmt->execute();
    
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
    
        return $result ? $result['profile_id'] : null;
    }
    


    public function saveUserProfile() {
        $db = Database::getPDO();
        
        $stmt = $db->prepare("INSERT INTO user_profiles (profile_name, is_suspended) 
                              VALUES (:name, :isSuspended)");
    
        $stmt->bindParam(':name', $this->name);
        $stmt->bindParam(':isSuspended', $this->isSuspended, PDO::PARAM_BOOL);
    
        return $stmt->execute();
    }   

    */

    // Views all Cleaning Services   
    public static function viewCleaningServices($accountId = null) {
            $db = Database::getPDO();
            
            // Prepare the SQL statement to fetch all cleaning services based on account_id
            if ($accountId !== null) {
                $stmt = $db->prepare("SELECT * FROM cleaner_services WHERE cleaner_account_id = :account_id");
                $stmt->execute([':account_id' => $accountId]);
            } else {
                $stmt = $db->prepare("SELECT * FROM cleaner_services");
                $stmt->execute();
            }
            
            // Fetch all the services as an associative array
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            // Initialize an empty array to hold the CleaningService objects
            $cleaningServices = [];
            
            // Iterate through the results and create a CleaningService object for each row
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
                    $row['updated_at']
                );
            }
            
            // Return the array of CleaningService objects
            return $cleaningServices;
        }
    
    

}
