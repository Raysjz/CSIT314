<?php
require_once(__DIR__ . '/../entities/UserProfile.php');

class SuspendUserProfileController {
    // Fetch user by ID
    public function getUserById($userId) {
        return UserProfile::getUserById($userId); // Return the user object from the database
    }

    // Suspend the user Profile
    public function suspendUserProfile($userId) {
        $user = $this->getUserById($userId);

        if ($user) {
            // Suspend the user by calling the suspendUserProfile method in the UserProfile entity
            return $user->suspendUserProfile();
        }
        
        return false; // User not found
    }
}
?>
