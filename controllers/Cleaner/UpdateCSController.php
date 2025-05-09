<?php
// Update Cleaning Service Controller

// Include dependencies
require_once __DIR__ . '/../../entities/CleaningService.php';

class UpdateCleaningServiceController
{
    // Fetch cleaning service by ID
    public function getCleaningServiceById($serviceId)
    {
        return CleaningService::getCleaningServiceById($serviceId);
    }

    // Update the cleaning service with new data
    public function updateCleaningService($data)
    {
        $service = new CleaningService(
            $data['service_id'],          // Service ID
            $data['cleaner_account_id'],  // Cleaner Account ID
            $data['category_id'],         // Category ID
            $data['title'],               // Title
            $data['description'],         // Description
            $data['price'],               // Price
            $data['availability'],        // Availability
            isset($data['is_suspended']) ? $data['is_suspended'] : false, // Suspension status
            isset($data['created_at']) ? $data['created_at'] : null,      // Created At (optional)
            isset($data['updated_at']) ? $data['updated_at'] : null       // Updated At (optional)
        );
        return $service->updateCleaningService();
    }
}
?>
