<?php
require_once(__DIR__ . '/../entities/CleaningServices.php');

class SearchCleaningServicesController {
    // Method to search for cleaning services based on search query and (optionally) cleaner account ID
    public function searchCleaningServices($searchQuery, $accountId = null) {
        return CleaningService::searchCleaningServices($searchQuery, $accountId);
    }
}
?>
