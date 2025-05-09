<?php
// Include necessary files
require_once(__DIR__ . '/../../entities/UserProfile.php');

class SuspendUserProfileController {
    // Get user profile by ID
    public function getUserProfileById($userId) {
        return UserProfile::getUserProfileById($userId);
    }

    // Suspend the user profile by ID
    public function suspendUserProfile($userId) {
        $user = $this->getUserProfileById($userId);
        if ($user) {
            return $user->suspendUserProfile();
        }
        return false;
    }
}
?>
