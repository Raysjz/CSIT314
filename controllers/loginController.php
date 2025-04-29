<?php
require_once(__DIR__ . '/../entities/UserAccount.php');

class UserAccountController {
    public function authenticate($username, $password, $profile) {
        // Establish database connection
        $conn = Database::getPgConnect();
        
        // Create a new UserAccount instance before calling the validateUser method
        $userAccount = new UserAccount(null, $username, $password, $profile, null, false);  // ID is null initially

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