<?php
require_once(__DIR__ . '/../entities/UserProfile.php');

class UserProfileController {
    // Fetch all profiles (for dropdowns)
    public function getProfiles() {
        return UserProfile::getProfiles();
    }

    // Fetch profile ID by profile name
    public function getProfileIdByName($profileName) {
        return UserProfile::getProfileIdByName($profileName);
    }
}
?>
