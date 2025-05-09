<?php
// Include necessary files
require_once(__DIR__ . '/../../entities/UserAccount.php');

class CreateUserAccountController {
    // Handle form submission and create user account
    public function handleFormSubmission($data) {
        $userAccount = new UserAccount(
            null,  // ID is auto-generated
            $data['username'],
            $data['password'],
            $data['fullname'],
            $data['email'],
            $data['profileName'],
            $data['profileId'],
            isset($data['isSuspended']) ? $data['isSuspended'] : false
        );

        $validationResult = $userAccount->validateUserAccount();
        if ($validationResult === "Validation passed.") {
            return $userAccount->saveUserAccount();
        } else {
            return $validationResult;
        }
    }
}
?>
