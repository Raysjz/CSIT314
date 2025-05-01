<?php

// controllers/ShortlistController.php
require_once(__DIR__ . '/../entities/Shortlist.php');

class ShortlistController {
    public function addToShortlist($homeownerAccountId, $serviceId) {
        return Shortlist::add($homeownerAccountId, $serviceId);
    }
    public function getShortlistedServices($homeownerAccountId) {
        return Shortlist::getShortlistedServices($homeownerAccountId);
    }
    public function removeFromShortlist($homeownerAccountId, $serviceId) {
        return Shortlist::removeFromShortlist($homeownerAccountId, $serviceId);
    }
}

?>