<?php
require_once(__DIR__ . '/../../entities/UserProfile.php');

/**
 * Controller for viewing user profiles.
 */
class ViewUserProfileController {
    /**
     * Retrieve all user profiles.
     */
    public static function viewUserProfiles() {
        return UserProfile::viewUserProfiles();
    }
}
?>
