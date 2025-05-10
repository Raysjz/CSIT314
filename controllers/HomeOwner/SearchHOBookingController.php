<?php
require_once(__DIR__ . '/../../entities/MatchingBooking.php');

class searchHOBookingController {
    public function searchHOBooking($homeownerAccountId, $categoryId = null, $startDate = null, $endDate = null, $perPage = 10, $offset = 0) {
        $data = MatchingBooking::searchHomeownerBookingsPaginated($homeownerAccountId, $categoryId, $startDate, $endDate, $perPage, $offset);
        $total = MatchingBooking::countSearchHomeownerBookings($homeownerAccountId, $categoryId, $startDate, $endDate);
        return ['data' => $data, 'total' => $total];
    }
}


?>
