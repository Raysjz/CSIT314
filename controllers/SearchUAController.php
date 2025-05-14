<?php
require_once(__DIR__ . '/../entities/UserAccount.php');

class SearchUserAccountController {
    public function searchUserAccounts($searchQuery, $perPage = 10, $offset = 0) {
        $data = UserAccount::searchUserAccounts($searchQuery, $perPage, $offset);
        $total = UserAccount::countSearchResults($searchQuery);
        return [
            'data' => $data,
            'total' => $total
        ];
    }
}

?>
