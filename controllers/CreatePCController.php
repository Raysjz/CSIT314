<?php
require_once(__DIR__ . '/../entities/PlatformCategory.php');

class CreatePlatformCategoryController{
    private $PlatformCategory;

    public function __construct(PlatformCategory $PlatformCategory) {
        $this->PlatformCategory = $PlatformCategory;
    }

    // Process the user creation
    public function processPlatformCategoryCreation() {
        $validationResult = $this->PlatformCategory->validateSC();
        
        if ($validationResult === "Validation passed.") {
            if ($this->PlatformCategory->savePlatformCategory()) {
                return true; // Success
            } else {
                return "Error saving profile.";
            }
        } else {
            return $validationResult;  // Return validation error message
        }
    }
    

    public function handleFormSubmission($data) {
        $this->PlatformCategory = new PlatformCategory(
            null,  // ID is auto-generated
            $data['name'],
            isset($data['isSuspended']) ? $data['isSuspended'] : false
        );

        return $this->processPlatformCategoryCreation();
    }
}
