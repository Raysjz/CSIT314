<?php

// Include dependencies
require_once __DIR__ . '/../../entities/CleaningService.php';

class ViewHOcleanerDetailsController
{
    // Get service by ID
    public function getService($serviceId) {
        return CleaningService::getCleaningServiceById($serviceId);
    }

    // Get cleaner by service ID
    public function getCleanerByService($serviceId) {
        return CleaningService::getCleanerByServiceId($serviceId);
    }

    // Get all services by cleaner ID
    public function getServicesByCleaner($cleanerId) {
        return CleaningService::getServicesByCleaner($cleanerId);
    }
}
?>

?>
