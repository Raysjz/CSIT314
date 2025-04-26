<?php

class Database {
    private static $host = 'localhost';
    private static $port = '5432';
    private static $dbname = 'csit314-database';
    private static $user = 'postgres';
    private static $password = '1234';

    // 1)PDO connection (OOP-friendly)
    public static function getPDO() {
        $dsn = "pgsql:host=" . self::$host . ";port=" . self::$port . ";dbname=" . self::$dbname;

        try {
            $conn = new PDO($dsn, self::$user, self::$password);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $conn;
        } catch (PDOException $e) {
            die("❌ PDO Connection failed: " . $e->getMessage());
        }
    }

    // 2)pg_connect connection (procedural)
    public static function getPgConnect() {
        $connStr = "host=" . self::$host .
                   " port=" . self::$port .
                   " dbname=" . self::$dbname .
                   " user=" . self::$user .
                   " password=" . self::$password;

        $conn = pg_connect($connStr);
        if (!$conn) {
            die("❌ pg_connect failed.");
        }
        return $conn;
    }
}
class UserProfile {
    protected $profile_id;
    protected $name;
    protected $isSuspended;

    // Constructor
    public function __construct($profile_id, $name, $isSuspended) {
        $this->profile_id = $profile_id;
        $this->name = $name;
        $this->isSuspended = $isSuspended;
    }

    // Getter methods
    public function getProfileId() {
        return $this->profile_id;
    }

    public function getName() {
        return $this->name;
    }

    public function getIsSuspended() {
        return $this->isSuspended;
    }

    // Validate user input data
    public function validateUP() {
        if (empty($this->name)) {
            return "All fields are required.";
        }

        $db = Database::getPDO(); 

        // Check if Profile Name already exists
        $stmt = $db->prepare("SELECT * FROM user_profiles WHERE name = :name");
        $stmt->bindParam(':name', $this->name);
        $stmt->execute();
        if ($stmt->rowCount() > 0) {
             return "Profile Name already exists.";
         }


        return "Validation passed.";
    }

    public function saveUserProfile() {
        $db = Database::getPDO();
        
        $stmt = $db->prepare("INSERT INTO user_profiles (name, is_suspended) 
                              VALUES (:name, :isSuspended)");
    
        $stmt->bindParam(':name', $this->name);
        $stmt->bindParam(':isSuspended', $this->isSuspended, PDO::PARAM_BOOL);
    
        return $stmt->execute();
    }

    // Views all user Profiles
    public static function viewUserProfiles() {
        $db = Database::getPDO();
    
        // Prepare the SQL statement to fetch user profiles
        $stmt = $db->prepare("SELECT * FROM user_profiles");  // Ensure the table name is correct
        $stmt->execute();
    
        // Fetch all the profiles as an associative array
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
        // Initialize an empty array to hold the UserProfile objects
        $userProfiles = [];
    
        // Iterate through the results and create a UserProfile object for each row
        foreach ($result as $row) {
            $userProfiles[] = new UserProfile(
                $row['profile_id'],
                $row['name'],
                isset($row['is_suspended']) ? (bool)$row['is_suspended'] : false
            );
        }
    
        // Return the array of UserProfile objects
        return $userProfiles;
    }
    

    // Search user profile by name or id
    public function searchUserProfiles($searchQuery) {
        $db = Database::getPDO();
        
        // Ensure the search query is wrapped with wildcards for partial matching
        $searchQuery = "%" . $searchQuery . "%";
    
        // Use ILIKE for case-insensitive search for 'name' and CAST for profile_id comparison
        $stmt = $db->prepare("SELECT * FROM user_profiles 
                              WHERE name ILIKE :searchQuery 
                              OR profile_id::text ILIKE :searchQuery");  // Cast profile_id to text for comparison
    
        // Bind the parameter to the query
        $stmt->bindParam(':searchQuery', $searchQuery, PDO::PARAM_STR);
    
        // Execute the query
        $stmt->execute();
    
        // Fetch results and map them to UserProfile objects
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $userProfiles = [];
    
        foreach ($result as $row) {
            $userProfiles[] = new UserProfile(
                $row['profile_id'],
                $row['name'],
                isset($row['is_suspended']) ? (bool)$row['is_suspended'] : false
            );
        }
    
        return $userProfiles;
    }
    
}
