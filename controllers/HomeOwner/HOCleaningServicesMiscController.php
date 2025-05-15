<?php
require_once __DIR__ . '/../../entities/CleaningService.php';

class HOCleaningServicesMiscController
{
    // Count all available cleaning services for homeowners
    public function countHOCleaningServices()
    {
        return CleaningService::countHOCleaningServices();
    }

    // Count search results for homeowner cleaning services
    public function countSearchHOCleaningServices($searchQuery)
    {
        return CleaningService::countSearchHOCleaningServices($searchQuery);
    }
}
?>
