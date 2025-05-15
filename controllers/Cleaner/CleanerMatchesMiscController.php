<?php
// Cleaner Matches Misc Controller

// Include dependencies
require_once __DIR__ . '/../../entities/MatchingBooking.php';

class CleanerMatchesMiscController
{
    // Count all matches for a given cleaner account
    public function countCleanerMatches($accountId)
    {
        return MatchingBooking::countCleanerMatches($accountId);
    }

    // Count search results for cleaner matches with filters
    public function countSearchCleanerMatches($cleanerAccountId, $categoryId = null, $startDate = null, $endDate = null)
    {
        return MatchingBooking::countSearchCleanerMatches($cleanerAccountId, $categoryId, $startDate, $endDate);
    }
}
?>
