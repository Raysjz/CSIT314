<?php
require_once(__DIR__ . '/../db.php');

class UserAccount {
    protected $id;
    protected $username;
    protected $password;
    protected $profile;
    protected $profileId;
    protected $isSuspended;

    public function __construct($id, $username, $password, $profile, $profileId, $isSuspended) {
        $this->id = $id;
        $this->username = $username;
        $this->password = $password;
        $this->profile = $profile;
        $this->profileId = $profileId;
        $this->isSuspended = $isSuspended;
    }

    // Getter methods
    public function getId() {
        return $this->id;
    }

    public function getUsername() {
        return $this->username;
    }

    public function getPassword() {
        return $this->password;
    }

    public function getProfile() {
        return $this->profile;
    }
    public function getProfileId() {
        return $this->profileId;
    }
    
    public function getIsSuspended() {
        return $this->isSuspended;
    }

    // Validate user login data
    public function validateUser($username, $password, $profile) {
        $conn = Database::getPgConnect(); // Ensure database connection is correct
    
        if (empty($username) || empty($profile)) {
            return ['error' => 'Username and profile are required.'];
        }
    
        // Query database to find user with username and profile
        $result = pg_query_params($conn, "SELECT * FROM user_accounts WHERE ua_username = $1 AND profile_name = $2", [$username, $profile]);
        $user = pg_fetch_assoc($result);
    
        if ($user) {
            // Check if the password matches
            if ($password === $user['ua_password']) {
                // Check if the user is suspended (1 means suspended)
                if ($user['is_suspended'] === 't') {
                    return ['error' => '❌ Your account is suspended. Please contact support.'];
                }
                // User is valid and not suspended, return success
                return [
                    'success' => true,
                    'user' => $user
                ];
            } else {
                return ['error' => '❌ Incorrect password.'];
            }
        } else {
            return ['error' => '❌ No user found with that username/profile.'];
        }
    }
    
    // Validate user input data
    public function validateUserAccount() {
        if (empty($this->username) || empty($this->password) || empty($this->profile)) {
            return "All fields are required.";
        }

        $db = Database::getPDO();

        // Check if username already exists
        $stmt = $db->prepare("SELECT * FROM user_accounts WHERE ua_username = :username");
        $stmt->bindParam(':username', $this->username);
        $stmt->execute();
        if ($stmt->rowCount() > 0) {
            return "Username already taken.";
        }

        return "Validation passed.";
    }

    public function saveUserAccount() {
        $db = Database::getPDO();
    
        // Insert into user_accounts with the profile_name, profile_id, and is_suspended
        $stmt = $db->prepare("INSERT INTO user_accounts (ua_username, ua_password, profile_name, profile_id, is_suspended) 
                              VALUES (:ua_username, :ua_password, :profile_name, :profile_id, :is_suspended)");
    
        // Bind parameters
        $stmt->bindParam(':ua_username', $this->username);
        $stmt->bindParam(':ua_password', $this->password);
        $stmt->bindParam(':profile_name', $this->profile);
        $stmt->bindParam(':profile_id', $this->profileId);
        $stmt->bindParam(':is_suspended', $this->isSuspended, PDO::PARAM_BOOL);
    
        return $stmt->execute();
    }
    
    

    // Views all user accounts
    public static function viewUserAccounts() {
        $db = Database::getPDO();

        $stmt = $db->prepare("SELECT * FROM user_accounts");
        $stmt->execute();

        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $userAccounts = [];
        foreach ($result as $row) {
            $userAccounts[] = new UserAccount(
                $row['account_id'],
                $row['ua_username'],
                $row['ua_password'],
                $row['profile_name'],
                $row['profile_id'],
                isset($row['is_suspended']) ? (bool)$row['is_suspended'] : false
            );
        }

        return $userAccounts;
    }

    // Search user accounts by username or id
    public static function searchUserAccounts($searchQuery) {
        $db = Database::getPDO();

        if (is_numeric($searchQuery)) {
            $stmt = $db->prepare("SELECT * FROM user_accounts WHERE account_id = :search");
        } else {
            $stmt = $db->prepare("SELECT * FROM user_accounts WHERE ua_username LIKE :search");
            $searchQuery = "%" . $searchQuery . "%";
        }

        $stmt->bindParam(':search', $searchQuery);
        $stmt->execute();

        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $userAccounts = [];
        foreach ($result as $row) {
            $userAccounts[] = new UserAccount(
                $row['account_id'],
                $row['ua_username'],
                $row['ua_password'],
                $row['profile_name'],
                isset($row['is_suspended']) ? (bool)$row['is_suspended'] : false
            );
        }

        return $userAccounts;
    }


    // Fetches user by ID
    public static function getAccountUserById($id) {
        $db = Database::getPDO();

        $stmt = $db->prepare("SELECT * FROM user_accounts WHERE account_id = :id");
        $stmt->bindParam(':id', $id);
        $stmt->execute();

        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            return new UserAccount(
                $user['account_id'],
                $user['ua_username'],
                $user['ua_password'],
                $user['profile_name'],
                $user['profile_id'],
                isset($user['is_suspended']) ? (bool)$user['is_suspended'] : false
            );
        } else {
            return null;
        }
    }

     // Updates user account by id
     public function updateUserAccount() {
        $db = Database::getPDO();
    
        // Make sure to use the correct column names in the SQL query
        $stmt = $db->prepare("UPDATE user_accounts 
                              SET ua_username = :username, ua_password = :password, profile_name = :profile, profile_id = :profileId, is_suspended = :is_suspended 
                              WHERE account_id = :id");
    
        $stmt->bindParam(':id', $this->id, PDO::PARAM_INT);  
        $stmt->bindParam(':username', $this->username);  
        $stmt->bindParam(':password', $this->password);  
        $stmt->bindParam(':profile', $this->profile);    
        $stmt->bindParam(':profileId', $this->profileId);   
        $stmt->bindParam(':is_suspended', $this->isSuspended, PDO::PARAM_BOOL);  
    
        return $stmt->execute();  // Execute the update query
    }
    

    // Suspend a user (set is_suspended to true)
    public function suspendUserAccount() {
        $db = Database::getPDO();

        $stmt = $db->prepare("UPDATE user_accounts SET is_suspended = true WHERE account_id = :id");
        $stmt->bindParam(':id', $this->id);

        // Use :id in bindParam instead of :profile_id
        $stmt->bindParam(':id', $this->id, PDO::PARAM_INT);  // Correctly binding the profile ID

        return $stmt->execute(); // Execute the query
    }
        
}
