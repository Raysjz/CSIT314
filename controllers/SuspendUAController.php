<?php
require_once(__DIR__ . '/../entities/UserAccount.php');

class SuspendUserAccountController {
    // Fetch user by ID
    public function getAccountUserById($userId) {
        return UserAccount::getAccountUserById($userId); // Return the user object from the database
    }

    // Suspend the user account
    public function suspendUserAccount($userId) {
        $user = $this->getAccountUserById($userId);

        if ($user) {
            // Suspend the user by calling the suspendUserAccount method in the UserAccount entity
            return $user->suspendUserAccount();
        }
        
        return false; // User not found
    }
}
?>
