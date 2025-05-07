<?php
require_once(__DIR__ . '/../../entities/PlatformCategory.php');

class SearchPlatformCategoryController {
    // In your SearchPlatformCategoryController
    public function searchPlatformCategory($searchQuery) {
    return PlatformCategory::searchPlatformCategory($searchQuery);
    }

}
?>
