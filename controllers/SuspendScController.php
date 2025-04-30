<?php
require_once(__DIR__ . '/../entities/ServiceCategory.php');

class suspendServiceCategoryController {
    // Fetch user by ID
    public function getServiceCategoryById($userId) {
        return ServiceCategory::getServiceCategoryById($userId); // Return the user object from the database
    }

    // Suspend the user Profile
    public function suspendServiceCategory($userId) {
        $user = $this->getServiceCategoryById($userId);

        if ($user) {
            // Suspend the user by calling the suspendUserProfile method in the UserProfile entity
            return $user->suspendServiceCategory();
        }
        
        return false; // User not found
    }
}
?>
