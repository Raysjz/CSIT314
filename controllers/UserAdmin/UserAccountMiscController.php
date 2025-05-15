<?php
// User Account View and Search Misc Controller

// Include dependencies
require_once __DIR__ . '/../../entities/UserAccount.php';

class UserAccountMiscController
{
    // Get total count of all users
    public function countAllUsers()
    {
        return UserAccount::countAllUsers();
    }

    // Count total count for search results
    public function countSearchResults($searchQuery)
    {
        return UserAccount::countSearchResults($searchQuery);
    }
}
?>
