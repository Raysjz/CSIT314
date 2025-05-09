<?php
// View Service Category Controller

// Include dependencies
require_once __DIR__ . '/../../entities/ServiceCategory.php';

// Controller for viewing all platform categories
class ViewServiceCategoryController
{
    // Retrieve paginated service categories
    public function viewServiceCategory($perPage = 10, $offset = 0)
    {
        $data = ServiceCategory::getPaginatedCategories($perPage, $offset);
        $total = ServiceCategory::countAllCategories();
        return [
            'data' => $data,
            'total' => $total
        ];
    }
}

?>
