<?php
require_once(__DIR__ . '/../../entities/MatchingBooking.php');

class searchHOBookingController {
    public function searchHOBooking($homeownerAccountId, $categoryId = null, $startDate = null, $endDate = null) {
        return MatchingBooking::searchHOBooking(
            $homeownerAccountId,
            $categoryId,
            $startDate,
            $endDate
        );
    }
}

?>
