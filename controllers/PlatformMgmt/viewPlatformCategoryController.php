<?php
require_once(__DIR__ . '/../../entities/PlatformMgmt/PlatformCategory.php');

/**
 * Controller for viewing all platform categories.
 * Acts as a mediator between the boundary and the entity.
 */
class ViewPlatformCategoryController {
    /**
     * Retrieve all service categories.
     * returns PlatformCategory[]
     */
    public static function viewPlatformCategory() {
        return PlatformCategory::viewPlatformCategory();
    }
}
?>
