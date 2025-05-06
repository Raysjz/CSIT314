<?php
require_once(__DIR__ . '/../entities/MatchingBooking.php');

class viewCleanerMatchesController {
    public function viewCleanerMatches($accountId) {
        return MatchingBooking::viewCleanerMatches($accountId);
    }
    
}
?>
