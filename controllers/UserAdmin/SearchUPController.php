<?php
// Search User Profile Controller

// Include dependencies
require_once __DIR__ . '/../../entities/UserProfile.php';

class SearchUserProfileController
{
    // Search user profiles by query with pagination
    public function searchUserProfiles($searchQuery, $perPage = 10, $offset = 0)
    {
        $data = UserProfile::searchUserProfiles($searchQuery, $perPage, $offset);
        $total = UserProfile::countSearchProfiles($searchQuery);
        return [
            'data' => $data,
            'total' => $total
        ];
    }
}
?>
