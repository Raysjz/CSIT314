<?php
// Update User Profile Controller

// Include dependencies
require_once __DIR__ . '/../../entities/UserProfile.php';

class UpdateUserProfileController
{
    // Fetch user profile by ID
    public function getUserProfileById($profileId)
    {
        return UserProfile::getUserProfileById($profileId);
    }

    // Update the user profile with the new data
    public function updateUserProfile($data)
    {
        $user = new UserProfile(
            $data['id'],              // Profile ID
            $data['name'],            // Profile Name
            (bool)$data['isSuspended'] // Ensure it's boolean
        );
        return $user->updateUserProfile();
    }
}
?>
