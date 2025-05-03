<?php
require_once(__DIR__ . '/../../entities/UserAccount.php');

class ViewUserAccountController {
    public function viewUserAccounts($searchQuery = null, $perPage = 10, $offset = 0) {
        if ($searchQuery) {
            $data = UserAccount::searchUserAccounts($searchQuery, $perPage, $offset);
            $total = UserAccount::countSearchResults($searchQuery);
        } else {
            $data = UserAccount::getPaginatedAccounts($perPage, $offset);
            $total = UserAccount::countAllUsers();
        }
        
        return [
            'data' => $data,
            'total' => $total
        ];
    }
}
?>
