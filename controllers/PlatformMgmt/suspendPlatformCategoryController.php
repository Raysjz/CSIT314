<?php
require_once(__DIR__ . '/../../entities/PlatformCategory.php');

/**
 * Controller for suspending platform categories.
 */
class SuspendPlatformCategoryController {
    // Fetch category by ID
    public function getPlatformCategoryById($categoryId) {
        return PlatformCategory::getPlatformCategoryById($categoryId);
    }

    // Suspend the category
    public function suspendPlatformCategory($categoryId) {
        $category = $this->getPlatformCategoryById($categoryId);
        if ($category) {
            return $category->suspendPlatformCategory();
        }
        return false; // Category not found
    }
}
?>
