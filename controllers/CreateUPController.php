<?php
require_once(__DIR__ . '/../entities/UserAccount.php');

class CreateUserAccountController {
    private $userAccount;

    public function __construct(UserAccount $userAccount) {
        $this->userAccount = $userAccount;
    }

    // Process the user creation
    public function processUserCreation() {
        $validationResult = $this->userAccount->validateUserAccount();
        
        if ($validationResult === "Validation passed.") {
            return $this->userAccount->saveUser();  // Save user to the database
        } else {
            return $validationResult;  // Return validation error message
        }
    }

    public function handleFormSubmission($data) {
        $this->userAccount = new UserAccount(
            null,  // ID is auto-generated
            $data['username'],
            $data['password'],
            $data['profile'],
            isset($data['is_suspended']) ? $data['is_suspended'] : false
        );

        return $this->processUserCreation();
    }
}
