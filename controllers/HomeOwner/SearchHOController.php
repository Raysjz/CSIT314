<?php

// Include dependencies
require_once __DIR__ . '/../../entities/CleaningService.php';

class SearchHOCleaningServicesController {
    // Method to search for cleaning services for homeowners
    public function searchHOCleaningServices($searchQuery) {
        return CleaningService::searchHOCleaningServices($searchQuery);
    }
}
?>
