<?php
// Include necessary files
require_once(__DIR__ . '/../../entities/UserProfile.php');

class UserProfileController {
    // Get all profiles
    public function getProfiles() {
        return UserProfile::getProfiles();
    }

    // Get profile ID by name
    public function getProfileIdByName($profileName) {
        return UserProfile::getProfileIdByName($profileName);
    }
}
?>
