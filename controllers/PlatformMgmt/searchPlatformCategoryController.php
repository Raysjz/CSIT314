<?php
require_once(__DIR__ . '/../../entities/PlatformMgmt/PlatformCategory.php');

class SearchPlatformCategoryController {
    // In your SearchPlatformCategoryController
    public function searchPlatformCategory($searchQuery) {
    return PlatformCategory::searchPlatformCategory($searchQuery);
    }

}
?>
