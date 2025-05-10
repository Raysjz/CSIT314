<?php
// View Cleaner Matches Controller

// Include dependencies
require_once __DIR__ . '/../../entities/MatchingBooking.php';

class ViewCleanerMatchesController
{
    // Retrieve paginated matches for a given cleaner account
    public function viewCleanerMatches($accountId, $perPage = 10, $offset = 0)
    {
        $data = MatchingBooking::getPaginatedCleanerMatches($accountId, $perPage, $offset);
        $total = MatchingBooking::countCleanerMatches($accountId);
        return [
            'data' => $data,
            'total' => $total
        ];
    }
}

?>
