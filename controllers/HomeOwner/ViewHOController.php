<?php
// View HO Cleaning Services Controller

// Include dependencies
require_once __DIR__ . '/../../entities/CleaningService.php';

class ViewHOCleaningServicesController
{
    // Retrieve paginated available (not suspended) cleaning services
    public function viewHOCleaningServices($perPage = 10, $offset = 0)
    {
        // Only return the paginated array of CleaningService objects
        return CleaningService::getPaginatedHOCleaningServices($perPage, $offset);
    }
}
?>
