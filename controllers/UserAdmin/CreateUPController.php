<?php
// Create User Profile Controller

// Include dependencies
require_once __DIR__ . '/../../entities/UserProfile.php';

class CreateUserProfileController
{
    // Handle user profile creation
    public function handleFormSubmission($data)
    {
        $userProfile = new UserProfile(
            null, // ID is auto-generated
            $data['name'],
            isset($data['isSuspended']) ? $data['isSuspended'] : false
        );

        $validationResult = $userProfile->validateUP();
        if ($validationResult === "Validation passed.") {
            if ($userProfile->saveUserProfile()) {
                return true;
            } else {
                return "Error saving profile.";
            }
        }
        return $validationResult;
    }
}
?>
