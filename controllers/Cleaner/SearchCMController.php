<?php
// Search Cleaner Matches Controller

// Include dependencies
require_once __DIR__ . '/../../entities/MatchingBooking.php';

class SearchCleanerMatchesController
{
    // Search cleaner matches with optional filters and pagination
    public function searchCleanerMatches($cleanerAccountId, $categoryId = null, $startDate = null, $endDate = null, $perPage = 10, $offset = 0)
    {
        $data = MatchingBooking::searchCleanerMatchesPaginated($cleanerAccountId, $categoryId, $startDate, $endDate, $perPage, $offset);
        $total = MatchingBooking::countSearchCleanerMatches($cleanerAccountId, $categoryId, $startDate, $endDate);
        return [
            'data' => $data,
            'total' => $total
        ];
    }
}

?>
