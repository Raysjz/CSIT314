<?php
// Search Cleaning Services Controller

// Include dependencies
require_once __DIR__ . '/../../entities/CleaningService.php';

class SearchCleaningServicesController
{
    // Search for cleaning services based on query and (optionally) cleaner account ID
    public function searchCleaningServices($searchQuery, $accountId = null)
    {
        return CleaningService::searchCleaningServices($searchQuery, $accountId);
    }
}
?>
