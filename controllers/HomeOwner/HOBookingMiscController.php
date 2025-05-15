<?php
require_once(__DIR__ . '/../../entities/MatchingBooking.php');

class HOBookingMiscController
{
    // Count all bookings for a homeowner
    public function countHomeownerBookings($homeownerAccountId)
    {
        return MatchingBooking::countHomeownerBookings($homeownerAccountId);
    }

    // Count search results for bookings with filters
    public function countSearchHomeownerBookings($homeownerAccountId, $categoryId = null, $startDate = null, $endDate = null)
    {
        return MatchingBooking::countSearchHomeownerBookings($homeownerAccountId, $categoryId, $startDate, $endDate);
    }
}
?>
