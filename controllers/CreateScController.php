<?php
require_once(__DIR__ . '/../entities/serviceCategory.php');

class CreateserviceCategoryController{
    private $serviceCategory;

    public function __construct(serviceCategory $serviceCategory) {
        $this->serviceCategory = $serviceCategory;
    }

    // Process the user creation
    public function processServiceCategoryCreation() {
        $validationResult = $this->serviceCategory->validateSC();
        
        if ($validationResult === "Validation passed.") {
            if ($this->serviceCategory->saveServiceCategory()) {
                return true; // Success
            } else {
                return "Error saving profile.";
            }
        } else {
            return $validationResult;  // Return validation error message
        }
    }
    

    public function handleFormSubmission($data) {
        $this->serviceCategory = new serviceCategory(
            null,  // ID is auto-generated
            $data['name'],
            isset($data['isSuspended']) ? $data['isSuspended'] : false
        );

        return $this->processServiceCategoryCreation();
    }
}
