<?php
// Suspend Cleaning Service Controller

// Include dependencies
require_once __DIR__ . '/../../entities/CleaningService.php';

class SuspendCleaningServiceController
{
    // Fetch service by ID
    public function getCleaningServiceById($serviceId)
    {
        return CleaningService::getCleaningServiceById($serviceId);
    }

    // Suspend the cleaning service
    public function suspendCleaningService($serviceId)
    {
        $service = $this->getCleaningServiceById($serviceId);
        if ($service) {
            return $service->suspendCleaningService();
        }
        return false; // Service not found
    }
}
?>
