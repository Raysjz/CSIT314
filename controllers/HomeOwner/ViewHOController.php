<?php

// Include dependencies
require_once __DIR__ . '/../../entities/CleaningService.php';

class ViewHOCleaningServicesController {
    // Method to retrieve all available (not suspended) cleaning services
    public static function viewHOCleaningServices() {
        return CleaningService::viewHOCleaningServices();
    }
}
?>
