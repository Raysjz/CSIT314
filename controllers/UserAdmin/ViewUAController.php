<?php
// View User Accounts

// Include dependencies
require_once __DIR__ . '/../../entities/UserAccount.php';

// Controller for viewing user accounts with pagination
class ViewUserAccountController
{
    // Get paginated user accounts (no search)
    public function viewUserAccounts($perPage = 10, $offset = 0)
    {
        $data = UserAccount::getPaginatedAccounts($perPage, $offset);
        $total = UserAccount::countAllUsers();

        return [
            'data' => $data,
            'total' => $total
        ];
    }
}
?>
