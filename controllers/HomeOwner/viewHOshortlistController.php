<?php
// View HO Short List Controller

// Include dependencies
require_once(__DIR__ . '/../../entities/Shortlist.php');

class ViewHOShortlistController
{
    // Get all shortlisted services for a homeowner
    public function getShortlistedServices($homeownerAccountId)
    {
        return Shortlist::getShortlistedServices($homeownerAccountId);
    }

    // Get shortlist ID for a homeowner and service
    public function getShortlistId($homeownerAccountId, $serviceId)
    {
        return Shortlist::getShortlistId($homeownerAccountId, $serviceId);
    }

    // Get shortlist by its ID
    public function getShortlistById($shortlistId)
    {
        return Shortlist::getById($shortlistId);
    }
}
?>
