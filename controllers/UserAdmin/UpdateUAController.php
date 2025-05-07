<?php
require_once(__DIR__ . '/../../entities/UserAccount.php');

/**
 * Controller for updating user accounts.
 */
class UpdateUserAccountController {
    /**
     * Retrieve a user account by ID.
     */
    public function getAccountUserById($userId) {
        return UserAccount::getAccountUserById($userId);
    }

    /**
     * Handle updating the user account.
     */
    public function updateUserAccount($data) {
        $user = new UserAccount(
            $data['userid'],                // User ID
            $data['username'],              // Username
            $data['password'],              // Password (should be hashed in entity if changed)
            $data['fullname'],
            $data['email'],
            $data['profileName'],
            $data['profileId'],
            isset($data['isSuspended']) ? $data['isSuspended'] : false
        );

        return $user->updateUserAccount();
    }
}
?>
