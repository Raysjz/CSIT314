<?php
require_once(__DIR__ . '/../entities/ServiceBooking.php');

class viewCleanerBookingHistoryController {
    public function viewCleanerBookingHistory($accountId) {
        return ServiceBooking::viewCleanerBookingHistory($accountId);
    }
}
?>
