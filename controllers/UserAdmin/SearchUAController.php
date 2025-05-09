<?php
// Search User Account Controller

// Include dependencies
require_once __DIR__ . '/../../entities/UserAccount.php';

// Controller for searching user accounts with pagination
class SearchUserAccountController
{
    // Search user accounts by query string with pagination
    public function searchUserAccounts($searchQuery, $perPage = 10, $offset = 0)
    {
        $accounts = UserAccount::searchUserAccountsPaginated($searchQuery, $perPage, $offset);
        $total = UserAccount::countSearchResults($searchQuery);

        return [
            'data' => $accounts,
            'total' => $total
        ];
    }
}
?>
