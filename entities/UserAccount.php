<?php
require_once(__DIR__ . '/ConnectiontoDB.php');

class UserAccount {
    protected $id;
    protected $username;
    protected $password;
    protected $fullName;      
    protected $email;        
    protected $profile;
    protected $profileId;
    protected $isSuspended;

    // Updated constructor with new fields
    public function __construct($id, $username, $password, $fullName, $email, $profile, $profileId, $isSuspended) {
        $this->id = $id;
        $this->username = $username;
        $this->password = $password;
        $this->fullName = $fullName;  
        $this->email = $email;         
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
    
    public function getFullName() {      
        return $this->fullName;
    }

    public function getEmail() {         
        return $this->email;
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



    // Validate User Account Login Data
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
        if (empty($this->fullName) || empty($this->username) || empty($this->email) || empty($this->password)) {
            return "All fields are required.";
        }
    
        if (!filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
            return "Invalid email format.";
        }
    
        if (strlen($this->password) < 6) {
            return "Password must be at least 6 characters long.";
        }
    
        $db = Database::getPDO();
    
        // Check if email already exists
        $stmt = $db->prepare("SELECT * FROM user_accounts WHERE email = :email");
        $stmt->bindParam(':email', $this->email);
        $stmt->execute();
        if ($stmt->rowCount() > 0) {
            return "Email already exists.";
        }
    
        // Check if username already exists
        $stmt = $db->prepare("SELECT * FROM user_accounts WHERE ua_username = :username");
        $stmt->bindParam(':username', $this->username);
        $stmt->execute();
        if ($stmt->rowCount() > 0) {
            return "Username already taken.";
        }
    
        return "Validation passed.";
    }
    
    // Inserts New UserAccount into Database
    public function saveUserAccount() {
        $db = Database::getPDO();
    
        $stmt = $db->prepare("INSERT INTO user_accounts 
            (ua_username, ua_password, full_name, email, profile_name, profile_id, is_suspended) 
            VALUES 
            (:ua_username, :ua_password, :full_name, :email, :profile_name, :profile_id, :is_suspended)");
    
        $stmt->bindParam(':ua_username', $this->username);
        $stmt->bindParam(':ua_password', $this->password);
        $stmt->bindParam(':full_name', $this->fullName);
        $stmt->bindParam(':email', $this->email);
        $stmt->bindParam(':profile_name', $this->profile);
        $stmt->bindParam(':profile_id', $this->profileId);
        $stmt->bindParam(':is_suspended', $this->isSuspended, PDO::PARAM_BOOL);
    
        return $stmt->execute();
    }
    
    
    // View all user accounts (ordered by ID ascending)
    public static function viewUserAccounts() {
        $db = Database::getPDO();

        $stmt = $db->prepare("SELECT * FROM user_accounts ORDER BY account_id ASC");
        $stmt->execute();

        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $userAccounts = [];
        foreach ($result as $row) {
            $userAccounts[] = new UserAccount(
                $row['account_id'],
                $row['ua_username'],
                $row['ua_password'],
                $row['full_name'], 
                $row['email'], 
                $row['profile_name'], 
                $row['profile_id'],
                isset($row['is_suspended']) ? (bool)$row['is_suspended'] : false
            );
        }

        return $userAccounts;
    }

    // Fetches user by ID
    public static function getAccountUserById($id) {
        $db = Database::getPDO();
    
        $stmt = $db->prepare("SELECT * FROM user_accounts WHERE account_id = :id");
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
    
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
    
        if ($user) {
            return new UserAccount(
                $user['account_id'],
                $user['ua_username'],
                $user['ua_password'],
                $user['full_name'], 
                $user['email'], 
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
    
        $stmt = $db->prepare("UPDATE user_accounts 
                              SET ua_username = :username, ua_password = :password, full_name = :fullName, email = :email, profile_name = :profile, profile_id = :profileId, is_suspended = :is_suspended 
                              WHERE account_id = :id");
    
        $stmt->bindParam(':id', $this->id, PDO::PARAM_INT);  
        $stmt->bindParam(':username', $this->username);  
        $stmt->bindParam(':password', $this->password);  
        $stmt->bindParam(':fullName', $this->fullName); // <-- match SQL
        $stmt->bindParam(':email', $this->email); 
        $stmt->bindParam(':profile', $this->profile);    
        $stmt->bindParam(':profileId', $this->profileId);   
        $stmt->bindParam(':is_suspended', $this->isSuspended, PDO::PARAM_BOOL);  
    
        return $stmt->execute();
    }
    
    

    // Suspend a user (set is_suspended to true)
    public function suspendUserAccount() {
        $db = Database::getPDO();

        $stmt = $db->prepare("UPDATE user_accounts SET is_suspended = true WHERE account_id = :id");
        $stmt->bindParam(':id', $this->id);

        $stmt->bindParam(':id', $this->id, PDO::PARAM_INT);  // Correctly binding the profile ID

        return $stmt->execute(); // Execute the query
    }

    // NEW METHODS FOR PAGINATION
    public static function getPaginatedAccounts($perPage = 10, $offset = 0) {
        $db = Database::getPDO();
        
        $stmt = $db->prepare("SELECT * FROM user_accounts 
                            ORDER BY account_id ASC 
                            LIMIT :limit OFFSET :offset");
        
        $stmt->bindValue(':limit', (int)$perPage, PDO::PARAM_INT);
        $stmt->bindValue(':offset', (int)$offset, PDO::PARAM_INT);
        $stmt->execute();

        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $userAccounts = [];
        foreach ($result as $row) {
            $userAccounts[] = new UserAccount(
                $row['account_id'],
                $row['ua_username'],
                $row['ua_password'],
                $row['full_name'], 
                $row['email'], 
                $row['profile_name'], 
                $row['profile_id'],
                isset($row['is_suspended']) ? (bool)$row['is_suspended'] : false
            );
        }

        return $userAccounts;
    }

    // Count all users
    public static function countAllUsers() {
        $db = Database::getPDO();
        $stmt = $db->query("SELECT COUNT(*) FROM user_accounts");
        return (int)$stmt->fetchColumn();
    }

    // Search with pagination
    public static function searchUserAccounts($searchQuery, $perPage, $offset) {
        $db = Database::getPDO();
        $pattern = "%$searchQuery%";
        $stmt = $db->prepare(
            "SELECT * FROM user_accounts
            WHERE ua_username ILIKE :pattern
                OR full_name ILIKE :pattern
                OR email ILIKE :pattern
                OR CAST(account_id AS TEXT) ILIKE :pattern
            LIMIT :limit OFFSET :offset"
        );
        $stmt->bindValue(':pattern', $pattern, PDO::PARAM_STR);
        $stmt->bindValue(':limit', (int)$perPage, PDO::PARAM_INT);
        $stmt->bindValue(':offset', (int)$offset, PDO::PARAM_INT);
        $stmt->execute();
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $accounts = [];
        foreach ($results as $row) {
            $accounts[] = new UserAccount(
                $row['account_id'],
                $row['ua_username'],
                $row['ua_password'],
                $row['full_name'],
                $row['email'],
                $row['profile_name'] ?? '',
                $row['profile_id'] ?? 0,
                isset($row['is_suspended']) ? (bool)$row['is_suspended'] : false
            );
        }
        return $accounts;
    }

    // Count search results
    public static function countSearchResults($searchQuery) {
        $db = Database::getPDO();
        $pattern = "%$searchQuery%";
        $stmt = $db->prepare(
            "SELECT COUNT(*) FROM user_accounts
            WHERE ua_username ILIKE :pattern
                OR full_name ILIKE :pattern
                OR email ILIKE :pattern
                OR CAST(account_id AS TEXT) ILIKE :pattern"
        );
        $stmt->bindValue(':pattern', $pattern, PDO::PARAM_STR);
        $stmt->execute();
        return (int)$stmt->fetchColumn();
    }


}



?>
