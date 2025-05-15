<?php
// Service Category Misc Controller

// Include dependencies
require_once __DIR__ . '/../../entities/ServiceCategory.php';

class ServiceCategoryMiscController
{
    // Count all categories
    public function countAllCategories()
    {
        return ServiceCategory::countAllCategories();
    }

    // Count search results for categories
    public function countSearchServiceCategory($searchQuery)
    {
        return ServiceCategory::countSearchServiceCategory($searchQuery);
    }
}
?>
