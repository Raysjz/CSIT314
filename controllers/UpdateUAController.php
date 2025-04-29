<?php
require_once(__DIR__ . '/../entities/UserAccount.php');

class UpdateUserAccountController {
    // Method to retrieve user by ID
    public function getAccountUserById($userId) {
        return UserAccount::getAccountUserById($userId);
    }

    // Method to handle updating the user account
    public function updateUserAccount($data) {
        $user = new UserAccount(
            $data['userid'],   // User ID
            $data['username'], // Username
            $data['password'], // Password
            $data['profileName'],
            $data['profileId'],
            isset($data['isSuspended']) ? $data['isSuspended'] : false
        );

        // Call updateUserAccount method in Entity to update the user in the database
        return $user->updateUserAccount();
    }
    
}
    
?>
