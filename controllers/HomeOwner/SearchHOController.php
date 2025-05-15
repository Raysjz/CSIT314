<?php
// Search HO Cleaning Services Controller

// Include dependencies
require_once __DIR__ . '/../../entities/CleaningService.php';

class SearchHOCleaningServicesController
{
    // Search for paginated cleaning services for homeowners
    public function searchHOCleaningServices($searchQuery, $perPage = 10, $offset = 0)
    {
        // Only return the paginated array of CleaningService objects
        return CleaningService::searchHOCleaningServicesPaginated($searchQuery, $perPage, $offset);
    }
}
?>
