<?php
// Cleaning Services Misc Controller

// Include dependencies
require_once __DIR__ . '/../../entities/CleaningService.php';

class CleaningServicesMiscController
{
    // Count all cleaning services (optionally filtered by account)
    public function countAllCleaningServices($accountId = null)
    {
        return CleaningService::countAllCleaningServices($accountId);
    }

    // Count search results for cleaning services (optionally filtered by account)
    public function countSearchCleaningServices($searchQuery, $accountId = null)
    {
        return CleaningService::countSearchCleaningServices($searchQuery, $accountId);
    }
}
?>
