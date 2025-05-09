<?php
// Create Service Category Controller

// Include dependencies
require_once __DIR__ . '/../../entities/ServiceCategory.php';

/**
 * Controller for creating a new platform category.
 * Handles validation and saving, and mediates between boundary and entity.
 */
class CreateServiceCategoryController
{
    private $ServiceCategory;

    // Constructor accepts a ServiceCategory entity
    public function __construct(ServiceCategory $ServiceCategory)
    {
        $this->ServiceCategory = $ServiceCategory;
    }

    // Validates and saves the category
    public function processServiceCategoryCreation()
    {
        $validationResult = $this->ServiceCategory->validatePC();
        if ($validationResult === "Validation passed.") {
            if ($this->ServiceCategory->saveServiceCategory()) {
                return true;
            }
            return "Error saving profile.";
        }
        return $validationResult;
    }

    // Handles form submission data, creates entity, and processes creation
    public function handleFormSubmission($data)
    {
        $this->ServiceCategory = new ServiceCategory(
            null, // ID is auto-generated
            $data['name'],
            isset($data['isSuspended']) ? $data['isSuspended'] : false
        );
        return $this->processServiceCategoryCreation();
    }
}
?>
