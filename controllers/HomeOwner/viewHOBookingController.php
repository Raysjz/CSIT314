<?php
// View HO booking Controller

// Include dependencies
require_once(__DIR__ . '/../../entities/MatchingBooking.php');

class viewHomeOwnerBookingsController
{
    // View paginated bookings for a homeowner
    public function viewHomeownerBookings($homeownerAccountId, $perPage = 10, $offset = 0)
    {
        $data = MatchingBooking::getPaginatedHomeownerBookings($homeownerAccountId, $perPage, $offset);
        $total = MatchingBooking::countHomeownerBookings($homeownerAccountId);
        return ['data' => $data, 'total' => $total];
    }
}
?>
