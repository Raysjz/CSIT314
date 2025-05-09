<?php
require_once(__DIR__ . '/../../entities/UserAccount.php');

/**
 * Controller responsible for searching user accounts with pagination.
 * 
 * This controller handles searching for user accounts based on a query string,
 * and returns paginated results using the UserAccount entity methods.
 */
class SearchUserAccountController {

    /**
     * Search for user accounts by a given query string, with pagination.
     *
     */
    public function searchUserAccounts($searchQuery, $perPage = 10, $offset = 0) {
        // Fetch paginated search results from the entity
        $accounts = UserAccount::searchUserAccounts($searchQuery, $perPage, $offset);

        // Get the total number of matching user accounts for pagination controls
        $total = UserAccount::countSearchResults($searchQuery);

        // Return both the current page search results and the total matches
        return [
            'data' => $accounts,
            'total' => $total
        ];
    }
}
?>
