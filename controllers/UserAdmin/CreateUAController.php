<?php
// Create User Account Controller

// Include dependencies
require_once __DIR__ . '/../../entities/UserAccount.php';

// Controller for creating user accounts (User Admin)
class CreateUserAccountController
{
    // Handle form submission and user account creation
    public function handleFormSubmission($data)
    {
        $userAccount = new UserAccount(
            null, // ID is auto-generated
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
        }
        return $validationResult;
    }
}
?>
