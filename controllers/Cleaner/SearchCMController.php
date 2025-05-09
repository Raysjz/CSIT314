<?php
// Search Cleaner Matches Controller

// Include dependencies
require_once __DIR__ . '/../../entities/MatchingBooking.php';

class SearchCleanerMatchesController
{
    // Search cleaner matches with optional filters
    public function searchCleanerMatches($cleanerAccountId, $categoryId = null, $startDate = null, $endDate = null)
    {
        return MatchingBooking::searchCleanerMatches(
            $cleanerAccountId,
            $categoryId,
            $startDate,
            $endDate
        );
    }
}
?>
