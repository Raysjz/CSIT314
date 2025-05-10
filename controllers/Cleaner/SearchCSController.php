<?php
// Search Cleaning Services Controller

// Include dependencies
require_once __DIR__ . '/../../entities/CleaningService.php';

class SearchCleaningServicesController
{
    // Search for cleaning services with pagination and optional account filter
    public function searchCleaningServices($searchQuery, $perPage = 10, $offset = 0, $accountId = null)
    {
        $data = CleaningService::searchCleaningServicesPaginated($searchQuery, $perPage, $offset, $accountId);
        $total = CleaningService::countSearchCleaningServices($searchQuery, $accountId);
        return [
            'data' => $data,
            'total' => $total
        ];
    }
}

?>
