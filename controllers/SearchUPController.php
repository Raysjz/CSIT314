<?php
// SearchUserProfileController.php
require_once(__DIR__ . '/../entities/UserProfile.php');

class SearchUserProfileController {
    // Method to search for user profiles based on search query
    public function searchUserProfiles($searchQuery) {
        $userProfile = new UserProfile(null, '', false);  // Instantiate UserProfile Entity

        // Return profiles that match the search query
        return $userProfile->searchUserProfiles($searchQuery);
    }
}

?>
