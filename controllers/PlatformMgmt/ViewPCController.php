<?php
require_once(__DIR__ . '/../../entities/PlatformMgmt/PlatformCategory.php');

class ViewPlatformCategoryController {

    // Method to retrieve service categories based on search query
    public function viewPlatformCategory($searchQuery = null) {
        // Instantiate PlatformCategory Entity
        $PlatformCategoryEntity = new PlatformCategory(null, '', 0);  // Empty fields to instantiate

        // If a search query exists, search for matching categories
        if ($searchQuery) {
            return $PlatformCategoryEntity->searchPlatformCategory($searchQuery);
        } else {
            // If no query, return all categories
            return $PlatformCategoryEntity->viewPlatformCategory();
        }
    }
}
?>
