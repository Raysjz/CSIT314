<?php
require_once(__DIR__ . '/../entities/PlatformCategory.php');

class suspendPlatformCategoryController {
    // Fetch user by ID
    public function getPlatformCategoryById($userId) {
        return PlatformCategory::getPlatformCategoryById($userId); // Return the user object from the database
    }

    // Suspend the user Profile
    public function suspendPlatformCategory($userId) {
        $user = $this->getPlatformCategoryById($userId);

        if ($user) {
            // Suspend the user by calling the suspendUserProfile method in the UserProfile entity
            return $user->suspendPlatformCategory();
        }
        
        return false; // User not found
    }
}
?>
