<?php
// UserAccount Controller (Misc)

// Include dependencies
require_once __DIR__ . '/../../entities/UserAccount.php';

class UserAccountController
{
    // Get user account by ID
    public function getUserById($id)
    {
        return UserAccount::getAccountUserById($id);
    }
}
?>
