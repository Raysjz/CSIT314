<?php
// View HO Short List Details Controller

// Include dependencies
require_once __DIR__ . '/../../entities/Shortlist.php';

class viewHOshortlistDetailsController
{
    // Get shortlist by its ID
    public function getShortlistById($shortlistId)
    {
        return Shortlist::getById($shortlistId);
    }
}
?>
