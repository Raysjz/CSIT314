<?php
// View Cleaning Services Controller

// Include dependencies
require_once __DIR__ . '/../../entities/CleaningService.php';

class ViewCleaningServicesController
{
    // Retrieve all cleaning services for a given account (or all if null)
    public static function viewCleaningServices($accountId = null)
    {
        return CleaningService::viewCleaningServices($accountId);
    }
}
?>
