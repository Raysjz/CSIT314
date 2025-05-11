<?php
require_once(__DIR__ . '/db.php');

class UserAccount {
    protected $id;
    protected $username;
    protected $password;
    protected $fullName;
    protected $email;
    protected $profile;
    protected $profileId;
    protected $isSuspended;

    // Constructor
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

    // --- Getters ---
    public function getId() { return $this->id; }
    public function getUsername() { return $this->username; }
    public function getPassword() { return $this->password; }
    public function getFullName() { return $this->fullName; }
    public function getEmail() { return $this->email; }
    public function getProfile() { return $this->profile; }
    public function getProfileId() { return $this->profileId; }
    public function getIsSuspended() { return $this->isSuspended; }

    // Validate login credentials
    public function validateUser($username, $password, $profile) {
        $conn = Database::getPgConnect();

        if (empty($username) || empty($profile)) {
            return ['error' => 'Username and profile are required.'];
        }

        $result = pg_query_params($conn, "SELECT * FROM user_accounts WHERE ua_username = $1 AND profile_name = $2", [$username, $profile]);
        $user = pg_fetch_assoc($result);

        if ($user) {
            // Use password_verify for real apps; here it's plain comparison
            if ($password === $user['ua_password']) {
                if ($user['is_suspended'] === 't') {
                    return ['error' => '❌ Your account is suspended. Please contact support.'];
                }
                return ['success' => true, 'user' => $user];
            } else {
                return ['error' => '❌ Incorrect password.'];
            }
        } else {
            return ['error' => '❌ No user found with that username/profile.'];
        }
    }
}

class UserAccountController {
    public function authenticate($username, $password, $profile) {
        // Create a new UserAccount instance before calling the validateUser method
        $userAccount = new UserAccount(null, $username, $password, null, null,  $profile, null, false);  // ID is null initially

        // Validate the user data
        $result = $userAccount->validateUser($username, $password, $profile);  // Now it's calling validateUser on the instantiated object
        
        if (isset($result['success']) && $result['success']) {
            // Retrieve profileId from the validated user
            $profileId = $result['user']['profile_id']; // Assuming 'profile_id' exists in your database record
            
            // Now instantiate the UserAccount object with all 6 required parameters
            $userAccount = new UserAccount(
                null,  // ID is null because it will be auto-generated
                $username,  // Passed from POST data
                $password,  // Passed from POST data
                null,       // Full Name not required
                null,       // Email Not required
                $profile,    // Passed from POST data
                $profileId,  // Retrieved profile_id from the database
                false         // Default suspension status (false)
            );
            
            // Return the user data
            return [
                'success' => true,
                'user' => $result['user']
            ];
        } else {
            return $result;  // Return the error message from validation
        }
    }
}


?>