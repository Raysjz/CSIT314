<?php
require_once(__DIR__ . '/../entities/ServiceBooking.php');

class SearchCleanerBookingHistoryController {
    public function searchCleanerBookingHistory($cleanerAccountId, $categoryId = null, $startDate = null, $endDate = null) {
        return ServiceBooking::searchCleanerBookingHistory(
            $cleanerAccountId,
            $categoryId,
            $startDate,
            $endDate
        );
    }
    
}
?>
