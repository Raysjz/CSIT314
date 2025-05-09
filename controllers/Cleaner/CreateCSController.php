<?php
// Create Cleaning Service Controller

// Include dependencies
require_once __DIR__ . '/../../entities/CleaningService.php';

class CreateCleaningServiceController
{
    private $cleaningService;

    public function __construct($cleaningService = null)
    {
        $this->cleaningService = $cleaningService;
    }

    // Validate and save the cleaning service
    public function processCleaningServiceCreation()
    {
        $validationResult = $this->cleaningService->validateCleaningService();
        if ($validationResult === "Validation passed.") {
            if ($this->cleaningService->saveCleaningService()) {
                return true;
            }
            return "Error saving cleaning service.";
        }
        return $validationResult;
    }

    // Handle form submission, create entity, and process creation
    public function handleFormSubmission($data)
    {
        $this->cleaningService = new CleaningService(
            null, // ID auto-generated
            $data['cleaner_account_id'],
            $data['category_id'],
            $data['title'],
            $data['description'],
            $data['price'],
            $data['availability'],
            isset($data['is_suspended']) ? $data['is_suspended'] : false
        );
        return $this->processCleaningServiceCreation();
    }
}
?>
