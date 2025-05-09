<?php
// Service Category Controller (Misc)

// Include dependencies
require_once __DIR__ . '/../../entities/ServiceCategory.php';

class ServiceCategoryController
{
    // Get all categories
    public function getAllCategories()
    {
        return ServiceCategory::getAllCategories();
    }

    // Get category by ID
    public function getCategoryById($id)
    {
        return ServiceCategory::getServiceCategoryById($id);
    }
}
?>
