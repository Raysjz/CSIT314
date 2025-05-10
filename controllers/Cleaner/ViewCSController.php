<?php
// View Cleaning Services Controller

// Include dependencies
require_once __DIR__ . '/../../entities/CleaningService.php';

class ViewCleaningServicesController
{
    // Retrieve paginated cleaning services for a given account (or all if null)
    public function viewCleaningServices($perPage = 10, $offset = 0, $accountId = null)
    {
        $data = CleaningService::getPaginatedCleaningServices($perPage, $offset, $accountId);
        $total = CleaningService::countAllCleaningServices($accountId);
        return [
            'data' => $data,
            'total' => $total
        ];
    }
}

?>
