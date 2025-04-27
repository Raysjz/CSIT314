<?php
// ViewUserProfileController.php
require_once(__DIR__ . '/../entities/UserProfile.php');

class ViewUserProfileController {
    // Method to retrieve all user profiles
    public function viewUserProfiles() {
        $userProfile = new UserProfile(null, '', false);  // Instantiate UserProfile Entity

        // Return all user profiles
        return $userProfile->viewUserProfiles();
    }
}

?>
