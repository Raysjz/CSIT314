<?php
// View HO booking Controller

// Include dependencies
require_once(__DIR__ . '/../../entities/MatchingBooking.php');

class viewHomeOwnerBookingsController
{
    // View paginated bookings for a homeowner
    public function viewHomeownerBookings($homeownerAccountId, $perPage = 10, $offset = 0)
    {
        // Only return the paginated array of MatchingBooking objects
        return MatchingBooking::getPaginatedHomeownerBookings($homeownerAccountId, $perPage, $offset);
    }
}
?>
