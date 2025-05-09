<?php
// Update UserAccount Controller

// Include dependencies
require_once __DIR__ . '/../../entities/UserAccount.php';

// Controller for updating user accounts
class UpdateUserAccountController
{
    // Get user account by ID
    public function getAccountUserById($userId)
    {
        return UserAccount::getAccountUserById($userId);
    }

    // Update user account with provided data
    public function updateUserAccount($data)
    {
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
