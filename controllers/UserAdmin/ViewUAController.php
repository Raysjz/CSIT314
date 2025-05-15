<?php
// View User Accounts Controller

// Include dependencies
require_once __DIR__ . '/../../entities/UserAccount.php';

// Controller for viewing user accounts with pagination
class ViewUserAccountController
{
    // Get paginated user accounts
    public function viewUserAccounts($perPage = 10, $offset = 0)
    {
        // Only return the paginated data
        return UserAccount::getPaginatedAccounts($perPage, $offset);
    }
}
?>
