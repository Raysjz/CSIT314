<?php
// Include necessary files
require_once(__DIR__ . '/../../entities/UserAccount.php');

class SuspendUserAccountController {
    // Fetch a user account by ID
    public function getAccountUserById($userId) {
        return UserAccount::getAccountUserById($userId);
    }

    // Suspend the user account with the given ID
    public function suspendUserAccount($userId) {
        $user = $this->getAccountUserById($userId);
        if ($user) {
            return $user->suspendUserAccount();
        }
        return false;
    }
}
?>
