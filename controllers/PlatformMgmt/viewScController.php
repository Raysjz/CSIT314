<?php
// View Service Category Controller

// Include dependencies
require_once __DIR__ . '/../../entities/ServiceCategory.php';

// Controller for viewing all platform categories
class ViewServiceCategoryController
{
    // Retrieve paginated service categories (returns array of objects)
    public function viewServiceCategory($perPage = 10, $offset = 0)
    {
        return ServiceCategory::getPaginatedCategories($perPage, $offset);
    }
}

?>



