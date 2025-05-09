<?php
// Suspend User Accounts

// Include dependencies
require_once __DIR__ . '/../../entities/UserAccount.php';

// Controller for suspending user accounts
class SuspendUserAccountController
{
    // Get user account by ID
    public function getAccountUserById($userId)
    {
        return UserAccount::getAccountUserById($userId);
    }

    // Suspend user account by ID
    public function suspendUserAccount($userId)
    {
        $user = $this->getAccountUserById($userId);
        if ($user) {
            return $user->suspendUserAccount();
        }
        return false;
    }
}
?>
