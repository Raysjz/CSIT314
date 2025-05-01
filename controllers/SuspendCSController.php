<?php
require_once(__DIR__ . '/../entities/CleaningServices.php');

class SuspendCleaningServiceController {
    // Fetch service by ID
    public function getCleaningServiceById($serviceId) {
        return CleaningService::getCleaningServiceById($serviceId); // Return the service object from the database
    }

    // Suspend the cleaning service
    public function suspendCleaningService($serviceId) {
        $service = $this->getCleaningServiceById($serviceId);

        if ($service) {
            // Suspend the service by calling the suspendCleaningService method in the CleaningService entity
            return $service->suspendCleaningService();
        }
        
        return false; // Service not found
    }
}
?>
