<?php

require_once(__DIR__ . '/../../entities/Shortlist.php');
require_once(__DIR__ . '/../../entities/CleaningServices.php');
require_once(__DIR__ . '/../../entities/ServiceView.php');


class DailyReportController {
    public function getDailyShortlistsAdded() {
        return Shortlist::countShortlistsAddedDaily();
    }

    public function getDailyServicesCreated() {
        return CleaningService::countCreatedDaily();
    }

    public function getDailyServiceViews() {
        return ServiceView::countViewsDaily();
    }
}


?>