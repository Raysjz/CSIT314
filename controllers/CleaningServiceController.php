<?php
require_once __DIR__ . '/../entities/CleaningService.php';

class CleaningServiceController {
    public function getCleaningServiceById($serviceId) {
        return CleaningService::getCleaningServiceById($serviceId);
    }
}
?>
