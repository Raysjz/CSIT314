<?php
// Search User Profile Controller

// Include dependencies
require_once __DIR__ . '/../../entities/UserProfile.php';

// Controller for searching user profiles with pagination
class SearchUserProfileController
{
    // Search user profiles by query with pagination (returns array of objects)
    public function searchUserProfiles($searchQuery, $perPage = 10, $offset = 0)
    {
        // Only return the paginated array of UserProfile objects matching the search
        return UserProfile::searchUserProfilesPaginated($searchQuery, $perPage, $offset);
    }
}
?>

