<?php
// Search HO Cleaning Services Controller

// Include dependencies
require_once __DIR__ . '/../../entities/CleaningService.php';

class SearchHOCleaningServicesController
{
    // Search for paginated cleaning services for homeowners
    public function searchHOCleaningServices($searchQuery, $perPage = 10, $offset = 0)
    {
        $data = CleaningService::searchHOCleaningServicesPaginated($searchQuery, $perPage, $offset);
        $total = CleaningService::countSearchHOCleaningServices($searchQuery);
        return [
            'data' => $data,
            'total' => $total
        ];
    }
}
?>
