<?php
// View User Profile Controller

// Include dependencies
require_once __DIR__ . '/../../entities/UserProfile.php';

// Controller for viewing user profiles with pagination
class ViewUserProfileController
{
    // Retrieve paginated user profiles
    public function viewUserProfiles($perPage = 10, $offset = 0)
    {
        $data = UserProfile::getPaginatedProfiles($perPage, $offset);
        $total = UserProfile::countAllProfiles();
        return [
            'data' => $data,
            'total' => $total
        ];
    }
}
?>
