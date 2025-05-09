<?php
// Include necessary files
require_once(__DIR__ . '/../../entities/UserProfile.php');

class ViewUserProfileController {
    // Retrieve paginated user profiles
    public function viewUserProfiles($perPage = 10, $offset = 0) {
        $data = UserProfile::getPaginatedProfiles($perPage, $offset);
        $total = UserProfile::countAllProfiles();

        return [
            'data' => $data,
            'total' => $total
        ];
    }
}
?>
