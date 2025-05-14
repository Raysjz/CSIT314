<?php
require_once(__DIR__ . '/../entities/UserAccount.php');

class CreateUserAccountController {
    private $userAccount;

    public function __construct(UserAccount $userAccount) {
        $this->userAccount = $userAccount;
    }

    // Process the user creation
    public function processUserAccountCreation() {
        $validationResult = $this->userAccount->validateUserAccount();
        
        if ($validationResult === "Validation passed.") {
            return $this->userAccount->saveUserAccount();  // Save user to the database
        } else {
            return $validationResult;  // Return validation error message
        }
    }

    public function handleFormSubmission($data) {
        $this->userAccount = new UserAccount(
            null,  // ID is auto-generated
            $data['username'],
            $data['password'],
            $data['fullname'],
            $data['email'],
            $data['profileName'],
            $data['profileId'],
            isset($data['isSuspended']) ? $data['isSuspended'] : false
        );

        return $this->processUserAccountCreation();
    }
}
