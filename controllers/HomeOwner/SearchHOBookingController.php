<?php
// Search HO booking controller

// Include dependencies
require_once(__DIR__ . '/../../entities/MatchingBooking.php');

class searchHOBookingController
{
    // Search paginated bookings for a homeowner with optional filters
    public function searchHOBooking($homeownerAccountId, $categoryId = null, $startDate = null, $endDate = null, $perPage = 10, $offset = 0)
    {
        $data = MatchingBooking::searchHomeownerBookingsPaginated($homeownerAccountId, $categoryId, $startDate, $endDate, $perPage, $offset);
        $total = MatchingBooking::countSearchHomeownerBookings($homeownerAccountId, $categoryId, $startDate, $endDate);
        return ['data' => $data, 'total' => $total];
    }
}
?>
