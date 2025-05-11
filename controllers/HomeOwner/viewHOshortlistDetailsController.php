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

        // If you want to fetch cleaner details, uncomment and use your user controller
        // $userController = new UserAccountController();
        // $cleaner = $userController->getUserById($service->getCleanerAccountId());

        $cleaner = null; // Placeholder if not fetching cleaner details
        return [$service, $cleaner];
    }
}
?>
