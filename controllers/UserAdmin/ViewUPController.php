<?php
// View User Profile Controller

// Include dependencies
require_once __DIR__ . '/../../entities/UserProfile.php';

// Controller for viewing user profiles with pagination
class ViewUserProfileController
{
    // Retrieve paginated user profiles (returns array of objects)
    public function viewUserProfiles($perPage = 10, $offset = 0)
    {
        // Only return the paginated data
        return UserProfile::getPaginatedProfiles($perPage, $offset);
    }
}
?>



