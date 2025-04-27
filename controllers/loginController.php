<?php
require_once(__DIR__ . '/../entities/UserAccount.php');

class UserAccountController {
    public function authenticate($username, $password, $profile) {
        // Correctly instantiate the UserAccount with all required parameters
        $userAccount = new UserAccount(null, $username, $password, $profile, false);  // ID is null (auto-generated), isSuspended is false by default

        // Validate the user credentials
        $result = $userAccount->validateUser($username, $password, $profile);

        if (isset($result['success']) && $result['success']) {
            // If user authentication is successful, return the user data
            return $result;
        } else {
            // If authentication fails (including suspended users), return the error
            return $result;
        }
    }
}


?>