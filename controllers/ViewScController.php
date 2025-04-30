<?php

require_once(__DIR__ . '/../entities/ServiceCategory.php');

class ViewServiceCategoryController {

    // Method to retrieve service categories based on search query
    public function viewServiceCategory($searchQuery = null) {
        // Instantiate ServiceCategory Entity
        $serviceCategoryEntity = new ServiceCategory(null, '', 0);  // Empty fields to instantiate

        // If a search query exists, search for matching categories
        if ($searchQuery) {
            return $serviceCategoryEntity->searchServiceCategory($searchQuery);
        } else {
            // If no query, return all categories
            return $serviceCategoryEntity->viewServiceCategory();
        }
    }
}
?>
