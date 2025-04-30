<?php
require_once(__DIR__ . '/../entities/ServiceCategory.php'); 

class UpdateServiceCategoryController {
    // Fetch user profile by ID
    public function getServiceCategoryById($userId) {
        return ServiceCategory::getServiceCategoryById($userId);  // Ensure this returns a valid user profile object
    }

    // Update the user profile with the new data
    public function updateServiceCategory($data) {
        $user = new ServiceCategory(
            $data['id'],          // Profile ID
            $data['name'],        // Profile Name
            isset($data['isSuspended']) ? $data['isSuspended'] : false  // Suspended status
        );

        return $user->updateServiceCategory();  // Return true if update is successful
    }
}




?>
