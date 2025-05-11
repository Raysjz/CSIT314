<?php
// View HO Short List Details Controller

// Include dependencies
require_once __DIR__ . '/../../entities/CleaningService.php';

class ViewHOshortlistDetailsController
{
    // Get a cleaning service and its cleaner (returns [service, cleaner])
    public function getServiceAndCleaner($serviceId)
    {
        $service = CleaningService::getCleaningServiceById($serviceId);
        if (!$service) return [null, null];

        $cleaner = null; 
        return [$service, $cleaner];
    }
}
?>
