<?php
// Include dependencies
require_once __DIR__ . '/../../entities/MatchingBooking.php';


class searchCleanerMatchesController {
    public function searchCleanerMatches($cleanerAccountId, $categoryId = null, $startDate = null, $endDate = null) {
        return MatchingBooking::searchCleanerMatches(
            $cleanerAccountId,
            $categoryId,
            $startDate,
            $endDate
        );
    }
}

?>
