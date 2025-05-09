<?php
require_once(__DIR__ . '/../../entities/MatchingBooking.php');

class viewHomeOwnerBookingsController {
    public function viewHomeownerBookings($homeownerAccountId) {
        return MatchingBooking::viewHomeownerBookings($homeownerAccountId);
    }

    
}
?>
