<?php
// View Cleaner Matches Controller

// Include dependencies
require_once __DIR__ . '/../../entities/MatchingBooking.php';

class ViewCleanerMatchesController
{
    // Retrieve all matches for a given cleaner account
    public function viewCleanerMatches($accountId)
    {
        return MatchingBooking::viewCleanerMatches($accountId);
    }
}
?>
