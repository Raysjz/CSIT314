<?php
require_once(__DIR__ . '/../entities/UserAccount.php');

class ViewUserAccountController {
    // Method to retrieve user accounts based on search query
    public function viewUserAccounts($searchQuery = null) {
        // Instantiate UserAccount Entity
        $userAccount = new UserAccount(null, '', '', '', null, 0); // Just to call methods, no need for real user data

        // If a search query exists, search for matching accounts
        if ($searchQuery) {
            return $userAccount->searchUserAccounts($searchQuery);
        } else {
            // If no query, return all user accounts
            return $userAccount->viewUserAccounts();
        }
    }
}
?>
