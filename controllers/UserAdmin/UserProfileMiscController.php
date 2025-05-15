<?php
// User Profile View and Search Misc Controller

// Include dependencies
require_once __DIR__ . '/../../entities/UserProfile.php';

class UserProfileMiscController
{
    // Get total count of all profiles
    public function countAllProfiles()
    {
        return UserProfile::countAllProfiles();
    }

    // Count search results for profiles
    public function countSearchProfiles($searchQuery)
    {
        return UserProfile::countSearchProfiles($searchQuery);
    }
}
?>
