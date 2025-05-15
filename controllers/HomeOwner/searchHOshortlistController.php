<?php
// Search HO Short List Controller

require_once __DIR__ . '/../../entities/Shortlist.php';

class SearchHOShortlistController
{
    // Search shortlisted services for a homeowner by query
    public function searchShortlistedServices($homeownerAccountId, $searchQuery)
    {
        return Shortlist::searchShortlistedServices($homeownerAccountId, $searchQuery);
    }
}
?>
