<?php
require_once(__DIR__ . '/../../entities/UserProfile.php');

/**
 * Controller for searching user profiles.
 */
class SearchUserProfileController {
    /**
     * Search user profiles by query.
     */
    public static function searchUserProfiles($searchQuery) {
        return UserProfile::searchUserProfiles($searchQuery);
    }
}
?>
