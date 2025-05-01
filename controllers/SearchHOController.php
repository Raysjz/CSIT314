<?php
require_once(__DIR__ . '/../entities/HomeOwner.php');

class SearchHOCleaningServicesController {
    // Method to search for cleaning services for homeowners
    public function searchHOCleaningServices($searchQuery) {
        return HomeownerCleaningService::searchHOCleaningServices($searchQuery);
    }
}
?>
