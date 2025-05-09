<?php
// Search Service Category Controller

// Include dependencies
require_once __DIR__ . '/../../entities/ServiceCategory.php';

class SearchServiceCategoryController
{
    // Search service categories by query with pagination
    public function searchServiceCategory($searchQuery, $perPage = 10, $offset = 0)
    {
        $data = ServiceCategory::searchServiceCategoryPaginated($searchQuery, $perPage, $offset);
        $total = ServiceCategory::countSearchServiceCategory($searchQuery);
        return [
            'data' => $data,
            'total' => $total
        ];
    }
}

?>
