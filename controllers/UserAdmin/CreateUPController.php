<?php
require_once(__DIR__ . '/../../entities/userProfile.php');

class CreateUserProfileController {
    private $userProfile;

    public function __construct(userProfile $userProfile) {
        $this->userProfile = $userProfile;
    }

    // Process the user creation
    public function processUserProfileCreation() {
        $validationResult = $this->userProfile->validateUP();
        
        if ($validationResult === "Validation passed.") {
            if ($this->userProfile->saveUserProfile()) {
                return true; // Success
            } else {
                return "Error saving profile.";
            }
        } else {
            return $validationResult;  // Return validation error message
        }
    }
    

    public function handleFormSubmission($data) {
        $this->userProfile = new userProfile(
            null,  // ID is auto-generated
            $data['name'],
            isset($data['isSuspended']) ? $data['isSuspended'] : false
        );

        return $this->processUserProfileCreation();
    }
}
