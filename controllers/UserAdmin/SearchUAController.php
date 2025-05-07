<?php
require_once(__DIR__ . '/../../entities/UserAccount.php');

/**
 * Controller for searching user accounts with pagination.
 */
class SearchUserAccountController {
    /**
     * Search user accounts and return paginated results.
     */
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
