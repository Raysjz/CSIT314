<?php
// Search User Account Controller

// Include dependencies
require_once __DIR__ . '/../../entities/UserAccount.php';

// Controller for searching user accounts with pagination
class SearchUserAccountController
{
    // Search user accounts by query with pagination (returns array of objects)
    public function searchUserAccounts($searchQuery, $perPage = 10, $offset = 0)
    {
        // Only return the paginated array of UserAccount objects matching the search
        return UserAccount::searchUserAccountsPaginated($searchQuery, $perPage, $offset);
    }
}
?>
