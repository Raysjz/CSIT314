<?php
require_once(__DIR__ . '/../entities/Homeowner.php');


class ViewHOServiceController {
    public function getServiceAndCleaner($serviceId) {
        $service = HomeownerCleaningService::getCleaningServiceById($serviceId);
        if (!$service) return [null, null];

        // If you don't have a UserAccountController or email, just use null or a placeholder
        // $userController = new UserAccountController();
        // $cleaner = $userController->getUserById($service->getCleanerAccountId());

        $cleaner = null; // or set to a placeholder array/object if you want

        return [$service, $cleaner];
    }
}

?>
