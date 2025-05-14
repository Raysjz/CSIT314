<?php
// ViewUserProfileController.php
require_once(__DIR__ . '/../entities/CleaningServices.php');

class ViewCleaningServicesController {
    // Method to retrieve all cleaning services
    public function viewCleaningServices($accountId) {
        // Call the static method directly from the entity class
        return CleaningService::viewCleaningServices($accountId);
    }
}


?>
