<?php
require_once(__DIR__ . '/../entities/userProfile.php');

class CreateUserProfileController {
    private $userProfile;

    public function __construct(userProfile $userProfile) {
        $this->userProfile = $userProfile;
    }

    // Process the user creation
    public function processUserProfileCreation() {
        $validationResult = $this->userProfile->validateUP();
        
        if ($validationResult === "Validation passed.") {
            return $this->userProfile->saveUserProfile();  // Save user to the database
        } else {
            return $validationResult;  // Return validation error message
        }
    }

    public function handleFormSubmission($data) {
        $this->userProfile = new userProfile(
            null,  // ID is auto-generated
            $data['name'],
            isset($data['is_suspended']) ? $data['is_suspended'] : false
        );

        return $this->processUserProfileCreation();
    }
}
