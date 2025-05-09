<?php
// Include necessary files
require_once(__DIR__ . '/../../entities/UserAccount.php');

class SearchUserAccountController {
    // Search for user accounts by query string with pagination
    public function searchUserAccounts($searchQuery, $perPage = 10, $offset = 0) {
        $accounts = UserAccount::searchUserAccounts($searchQuery, $perPage, $offset);
        $total = UserAccount::countSearchResults($searchQuery);

        return [
            'data' => $accounts,
            'total' => $total
        ];
    }
}
?>
