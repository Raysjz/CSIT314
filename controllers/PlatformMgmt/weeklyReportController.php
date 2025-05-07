<?php

require_once(__DIR__ . '/../../entities/Shortlist.php');
require_once(__DIR__ . '/../../entities/CleaningServices.php');
require_once(__DIR__ . '/../../entities/ServiceView.php');


class WeeklyReportController {
    public function getWeeklyShortlistsAdded() {
        return Shortlist::countShortlistsAddedWeekly();
    }

    public function getWeeklyServicesCreated() {
        return CleaningService::countCreatedWeekly();
    }

    public function getWeeklyServiceViews() {
        return ServiceView::countViewsWeekly();
    }
}


?>