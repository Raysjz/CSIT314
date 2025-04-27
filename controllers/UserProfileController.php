<?php
require_once(__DIR__ . '/../entities/UserProfile.php');  // Include UserProfile entity

class UserProfileController {
    // Method to fetch all user profiles for the dropdown
    public function getProfiles() {
        // Use the UserProfile entity's getProfiles method to fetch profiles
        return UserProfile::getProfiles();
    }
}
?>
