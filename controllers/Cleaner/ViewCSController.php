<?php
// View Cleaning Services Controller

// Include dependencies
require_once __DIR__ . '/../../entities/CleaningService.php';

class ViewCleaningServicesController
{
    // Retrieve paginated cleaning services for a given account (or all if null)
    public function viewCleaningServices($perPage = 10, $offset = 0, $accountId = null)
    {
        // Only return the paginated array of CleaningService objects
        return CleaningService::getPaginatedCleaningServices($perPage, $offset, $accountId);
    }
}

?>
