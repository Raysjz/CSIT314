<?php
require_once(__DIR__ . '/../entities/PlatformCategory.php');

class PlatformCategoryController {
    public function getAllCategories() {
        return PlatformCategory::getAllCategories();
    }
}
?>
