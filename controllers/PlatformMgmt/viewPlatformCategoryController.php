<?php
require_once(__DIR__ . '/../../entities/PlatformMgmt/PlatformCategory.php');

class ViewPlatformCategoryController {

    // Method to retrieve all service categories (no search)
    public function viewPlatformCategory() {
        $platformCategory = new PlatformCategory(null, '', 0);  // Instantiate with empty/default values
        return $platformCategory->viewPlatformCategory();
    }
}
?>
