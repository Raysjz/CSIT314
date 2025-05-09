<?php
// Include necessary files
require_once(__DIR__ . '/../../entities/UserAccount.php');

class UpdateUserAccountController {
    // Retrieve a user account by ID
    public function getAccountUserById($userId) {
        return UserAccount::getAccountUserById($userId);
    }

    // Update the user account with new data
    public function updateUserAccount($data) {
        $user = new UserAccount(
            $data['userid'],
            $data['username'],
            $data['password'],
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
