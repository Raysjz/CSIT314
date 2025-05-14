<?php
require_once(__DIR__ . '/../entities/PlatformCategory.php'); 

class UpdatePlatformCategoryController {
    // Fetch user profile by ID
    public function getPlatformCategoryById($userId) {
        return PlatformCategory::getPlatformCategoryById($userId);  // Ensure this returns a valid user profile object
    }

    // Update the user profile with the new data
    public function updatePlatformCategory($data) {
        $user = new PlatformCategory(
            $data['id'],          // Profile ID
            $data['name'],        // Profile Name
            isset($data['isSuspended']) ? $data['isSuspended'] : false  // Suspended status
        );

        return $user->updatePlatformCategory();  // Return true if update is successful
    }
}




?>
