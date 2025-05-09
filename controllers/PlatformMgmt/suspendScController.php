<?php
// Suspend Service Category Controller

// Include dependencies
require_once __DIR__ . '/../../entities/ServiceCategory.php';

// Controller for suspending platform categories
class SuspendServiceCategoryController
{
    // Fetch category by ID
    public function getServiceCategoryById($categoryId)
    {
        return ServiceCategory::getServiceCategoryById($categoryId);
    }

    // Suspend the category
    public function suspendServiceCategory($categoryId)
    {
        $category = $this->getServiceCategoryById($categoryId);
        if ($category) {
            return $category->suspendServiceCategory();
        }
        return false; // Category not found
    }
}
?>
