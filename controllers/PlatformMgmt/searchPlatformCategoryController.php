<?php
require_once(__DIR__ . '/../../entities/PlatformMgmt/PlatformCategory.php');

class SearchPlatformCategoryController {

    // Method to search for categories based on search query
    public function searchPlatformCategory($searchQuery) {
        $platformCategory = new PlatformCategory(null, '', 0);  // Instantiate with empty/default values
        return $platformCategory->searchPlatformCategory($searchQuery);
    }
}
?>
