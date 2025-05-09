<?php
require_once(__DIR__ . '/../entities/HomeOwner.php');

class ViewHOCleaningServicesController {
    // Method to retrieve all available (not suspended) cleaning services
    public function viewHOCleaningServices() {
        // Call the static method without accountId
        return HomeownerCleaningService::viewHOCleaningServices();
    }
}
?>
