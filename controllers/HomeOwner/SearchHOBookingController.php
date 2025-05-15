<?php
// Search HO booking controller

// Include dependencies
require_once(__DIR__ . '/../../entities/MatchingBooking.php');

class searchHOBookingController
{
    // Search paginated bookings for a homeowner with optional filters
    public function searchHOBooking($homeownerAccountId, $categoryId = null, $startDate = null, $endDate = null, $perPage = 10, $offset = 0)
    {
        // Only return the paginated array of MatchingBooking objects
        return MatchingBooking::searchHomeownerBookingsPaginated($homeownerAccountId, $categoryId, $startDate, $endDate, $perPage, $offset);
    }
}
?>
