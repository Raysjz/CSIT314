<?php
require_once(__DIR__ . '/../entities/UserProfile.php'); 

class UpdateUserProfileController {
    // Fetch user profile by ID
    public function getUserProfileById($userId) {
        return UserProfile::getUserProfileById($userId);  // Ensure this returns a valid user profile object
    }

    // Update the user profile with the new data
    public function updateUserProfile($data) {
        $user = new UserProfile(
            $data['id'],          // Profile ID
            $data['name'],        // Profile Name
            isset($data['isSuspended']) ? $data['isSuspended'] : false  // Suspended status
        );

        return $user->updateUserProfile();  // Return true if update is successful
    }
}




?>
