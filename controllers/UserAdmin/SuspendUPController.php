<?php
// Suspend User Profile Controller

// Include dependencies
require_once __DIR__ . '/../../entities/UserProfile.php';

class SuspendUserProfileController
{
    // Fetch user profile by ID
    public function getUserProfileById($userId)
    {
        return UserProfile::getUserProfileById($userId);
    }

    // Suspend user profile by ID
    public function suspendUserProfile($userId)
    {
        $user = $this->getUserProfileById($userId);
        if ($user) {
            return $user->suspendUserProfile();
        }
        return false;
    }
}
?>
