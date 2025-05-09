<?php
// Include necessary files
require_once(__DIR__ . '/../../entities/UserProfile.php');

class CreateUserProfileController {
    // Process the user profile creation form submission
    public function handleFormSubmission($data) {
        $userProfile = new UserProfile(
            null,  // ID is auto-generated
            $data['name'],
            isset($data['isSuspended']) ? $data['isSuspended'] : false
        );

        $validationResult = $userProfile->validateUP();
        if ($validationResult === "Validation passed.") {
            if ($userProfile->saveUserProfile()) {
                return true; // Success
            } else {
                return "Error saving profile.";
            }
        } else {
            return $validationResult;  // Return validation error message
        }
    }
}
?>
