<?php
require_once(__DIR__ . '/../entities/UserAccount.php');

class UpdateUserAccountController {
    // Method to retrieve user by ID
    public function getUserById($userId) {
        return UserAccount::getUserById($userId);
    }

    // Method to handle updating the user account
    public function updateUserAccount($data) {
        $user = new UserAccount(
            $data['userid'],   // User ID
            $data['username'], // Username
            $data['password'], // Password
            $data['profile'],  // Profile
            isset($data['is_suspended']) ? $data['is_suspended'] : false  // Suspended status
        );

        // Call updateUserAccount method in Entity to update the user in the database
        return $user->updateUserAccount();
    }
}
?>
