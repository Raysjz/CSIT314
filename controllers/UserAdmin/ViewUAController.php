<?php
require_once(__DIR__ . '/../../entities/UserAccount.php');

/**
 * Controller responsible for viewing (listing) user accounts with pagination.
 * 
 * This controller does NOT handle search functionality. It simply retrieves
 * all user accounts, paginated, using the UserAccount entity methods.
 */
class ViewUserAccountController {

    /**
     *
     */
    public function viewUserAccounts($perPage = 10, $offset = 0) {
        // Fetch paginated user accounts from the entity
        $data = UserAccount::getPaginatedAccounts($perPage, $offset);

        // Get the total number of user accounts for pagination controls
        $total = UserAccount::countAllUsers();

        // Return both the current page data and the total count
        return [
            'data' => $data,
            'total' => $total
        ];
    }
}
?>
