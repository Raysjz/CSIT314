<?php
require_once(__DIR__ . '/../db.php');

class UserProfile {
    protected $id;
    protected $name;
    protected $isSuspended;

    // Constructor
    public function __construct($id, $name, $isSuspended) {
        $this->id = $id;
        $this->name = $name;
        $this->isSuspended = $isSuspended;
    }

    // Getter methods
    public function getProfileId() {
        return $this->id;
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
                $row['profile_name'],
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
                              WHERE profile_name ILIKE :searchQuery 
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
                $row['profile_name'],
                isset($row['is_suspended']) ? (bool)$row['is_suspended'] : false
            );
        }
    
        return $userProfiles;
    }
    

    // Fetch user by ID
    public static function getUserProfileById($id) {
        $db = Database::getPDO();
        
        $stmt = $db->prepare("SELECT * FROM user_profiles WHERE profile_id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
    
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
    
        if ($user) {
            return new UserProfile(
                $user['profile_id'],
                $user['profile_name'],
                isset($user['is_suspended']) ? (bool)$user['is_suspended'] : false
            );
        } else {
            // Debugging: If no user is found
            echo "No user found with profile_id: " . htmlspecialchars($id) . "<br>";
            return null; // Return null if user not found
        }
    }
    

    // Update user profile (method to handle update in the database)
    public function updateUserProfile() {
        $db = Database::getPDO();

        $stmt = $db->prepare("UPDATE user_profiles SET profile_name = :name, is_suspended = :isSuspended WHERE profile_id = :id");
        $stmt->bindParam(':id', $this->id, PDO::PARAM_INT);
        $stmt->bindParam(':name', $this->name);
        $stmt->bindParam(':isSuspended', $this->isSuspended, PDO::PARAM_BOOL);

        return $stmt->execute(); // Return whether the update was successful
    }

    // Suspend a user (set is_suspended to true)
    public function suspendUserProfile() {
        $db = Database::getPDO();
    
        // Ensure the SQL query uses the correct placeholder :id
        $stmt = $db->prepare("UPDATE user_profiles SET is_suspended = true WHERE profile_id = :id");
    
        // Use :id in bindParam instead of :profile_id
        $stmt->bindParam(':id', $this->id, PDO::PARAM_INT);  // Correctly binding the profile ID
    
        return $stmt->execute();  // Execute the query
    }
    
}
