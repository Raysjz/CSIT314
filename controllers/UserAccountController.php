<?php
// controllers/UserAccountController.php
require_once(__DIR__ . '/../entities/UserAccount.php');

class UserAccountController {
    public function getUserById($id) {
        return UserAccount::getAccountUserById($id);
    }

    public function generateUsersCreatedAtNow() {
    return UserAccount::countUsersCreatedToday();
    }   

    public function getActiveSuspendedCountsByProfile() {
    return UserAccount::countUsersCreatedToday();
    }  

    public function getTotalAccountsByProfile() {
    return UserAccount::countUsersCreatedToday();
    }  


    public function getAllProfilesWithAccountCounts() {
    return UserAccount::countUsersCreatedToday();
    }  


}

?>