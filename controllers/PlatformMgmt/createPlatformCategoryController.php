<?php
require_once(__DIR__ . '/../../entities/PlatformCategory.php');

/**
 * Controller for creating a new platform category.
 * Handles validation and saving, and mediates between boundary and entity.
 */
class CreatePlatformCategoryController {
    private $PlatformCategory;

    /**
     * Constructor accepts a PlatformCategory entity.
     */
    public function __construct(PlatformCategory $PlatformCategory) {
        $this->PlatformCategory = $PlatformCategory;
    }

    /**
     * Validates and saves the category.
     */
    public function processPlatformCategoryCreation() {
        $validationResult = $this->PlatformCategory->validatePC();
        if ($validationResult === "Validation passed.") {
            if ($this->PlatformCategory->savePlatformCategory()) {
                return true;
            } else {
                return "Error saving profile.";
            }
        } else {
            return $validationResult;
        }
    }

    /**
     * Handles form submission data, creates entity, and processes creation.
     */
    public function handleFormSubmission($data) {
        $this->PlatformCategory = new PlatformCategory(
            null, // ID is auto-generated
            $data['name'],
            isset($data['isSuspended']) ? $data['isSuspended'] : false
        );
        return $this->processPlatformCategoryCreation();
    }
}
?>
