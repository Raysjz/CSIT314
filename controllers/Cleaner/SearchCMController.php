<?php
// Search Cleaner Matches Controller

// Include dependencies
require_once __DIR__ . '/../../entities/MatchingBooking.php';

class SearchCleanerMatchesController
{
    // Search cleaner matches with optional filters and pagination
    public function searchCleanerMatches($cleanerAccountId, $categoryId = null, $startDate = null, $endDate = null, $perPage = 10, $offset = 0)
    {
        // Only return the paginated array of MatchingBooking objects
        return MatchingBooking::searchCleanerMatchesPaginated($cleanerAccountId, $categoryId, $startDate, $endDate, $perPage, $offset);
    }
}

?>
