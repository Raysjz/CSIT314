<?php
// Update Service Category Controller

// Include dependencies
require_once __DIR__ . '/../../entities/ServiceCategory.php';

// Controller for updating platform categories
class UpdateServiceCategoryController
{
    // Fetch category by ID
    public function getServiceCategoryById($categoryId)
    {
        return ServiceCategory::getServiceCategoryById($categoryId);
    }

    // Update the category with the new data
    public function updateServiceCategory($data)
    {
        $category = new ServiceCategory(
            $data['id'],
            $data['name'],
            isset($data['isSuspended']) ? $data['isSuspended'] : false
        );
        return $category->updateServiceCategory();
    }
}
?>
