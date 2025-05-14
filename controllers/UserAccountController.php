<?php
// controllers/UserAccountController.php
require_once(__DIR__ . '/../entities/UserAccount.php');

class UserAccountController {
    public function getUserById($id) {
        return UserAccount::getAccountUserById($id);
    }
}

?>