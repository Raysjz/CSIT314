<?php
// Include necessary files
require_once(__DIR__ . '/../../entities/UserAccount.php');

class ViewUserAccountController {
    // Retrieve paginated user accounts
    public function viewUserAccounts($perPage = 10, $offset = 0) {
        $data = UserAccount::getPaginatedAccounts($perPage, $offset);
        $total = UserAccount::countAllUsers();

        return [
            'data' => $data,
            'total' => $total
        ];
    }
}
?>
