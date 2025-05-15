<?php
// View Cleaner Matches Controller

// Include dependencies
require_once __DIR__ . '/../../entities/MatchingBooking.php';

class ViewCleanerMatchesController
{
    // Retrieve paginated matches for a given cleaner account
    public function viewCleanerMatches($accountId, $perPage = 10, $offset = 0)
    {
        // Only return the paginated array of MatchingBooking objects
        return MatchingBooking::getPaginatedCleanerMatches($accountId, $perPage, $offset);
    }
}

?>
