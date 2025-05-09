<?php

require_once(__DIR__ . '/../../entities/Shortlist.php');

class ViewHOShortlistController {
    public function getShortlistedServices($homeownerAccountId) {
        return Shortlist::getShortlistedServices($homeownerAccountId);
    }
    public function getShortlistId($homeownerAccountId, $serviceId) {
        return Shortlist::getShortlistId($homeownerAccountId, $serviceId);
    }
    public function getShortlistById($shortlistId) {
        return Shortlist::getById($shortlistId);
    }
    
}
?>
