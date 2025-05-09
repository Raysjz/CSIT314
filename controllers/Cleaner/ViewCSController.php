<?php
// Include dependencies
require_once __DIR__ . '/../../entities/CleaningService.php';

class ViewCleaningServicesController {
    // Method to retrieve all cleaning services
    public static function viewCleaningServices($accountId) {
        return CleaningService::viewCleaningServices($accountId);
    }
}


?>
