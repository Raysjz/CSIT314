<?php
require_once(__DIR__ . '/../../entities/PlatformMgmt/PlatformCategory.php');

/**
 * Controller for updating platform categories.
 */
class UpdatePlatformCategoryController {
    /**
     * Fetch category by ID.
     */
    public function getPlatformCategoryById($categoryId) {
        return PlatformCategory::getPlatformCategoryById($categoryId);
    }

    /**
     * Update the category with the new data.
     */
    public function updatePlatformCategory($data) {
        $category = new PlatformCategory(
            $data['id'],
            $data['name'],
            isset($data['isSuspended']) ? $data['isSuspended'] : false
        );
        return $category->updatePlatformCategory();
    }
}
?>
