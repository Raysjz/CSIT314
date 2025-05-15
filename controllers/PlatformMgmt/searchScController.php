<?php
// Search Service Category Controller

// Include dependencies
require_once __DIR__ . '/../../entities/ServiceCategory.php';

class SearchServiceCategoryController
{
    // Search service categories by query with pagination (returns array of objects)
    public function searchServiceCategory($searchQuery, $perPage = 10, $offset = 0)
    {
        return ServiceCategory::searchServiceCategoryPaginated($searchQuery, $perPage, $offset);
    }
}

?>