<?php
require_once(__DIR__ . '/../entities/CleaningServices.php');

class CreateCleaningServiceController {
    private $cleaningService;

    public function __construct($cleaningService = null) {
        $this->cleaningService = $cleaningService;
    }

    public function processCleaningServiceCreation() {
        $validationResult = $this->cleaningService->validateCleaningService();
        if ($validationResult === "Validation passed.") {
            if ($this->cleaningService->saveCleaningService()) {
                return true;
            } else {
                return "Error saving cleaning service.";
            }
        } else {
            return $validationResult;
        }
    }

    public function handleFormSubmission($data) {
        $this->cleaningService = new CleaningService(
            null, // ID auto-generated
            $data['cleaner_account_id'],
            $data['category_id'],
            $data['title'],
            $data['description'],
            $data['price'],
            $data['availability'],
            $data['is_suspended']
        );
        return $this->processCleaningServiceCreation();
    }
}
?>
