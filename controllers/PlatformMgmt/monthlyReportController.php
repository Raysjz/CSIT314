<?php

require_once(__DIR__ . '/../../entities/Shortlist.php');
require_once(__DIR__ . '/../../entities/CleaningServices.php');
require_once(__DIR__ . '/../../entities/ServiceView.php');


class MonthlyReportController {
    public function getMonthlyShortlistsAdded() {
        return Shortlist::countShortlistsAddedMonthly();
    }

    public function getMonthlyServicesCreated() {
        return CleaningService::countCreatedMonthly();
    }

    public function getMonthlyServiceViews() {
        return ServiceView::countViewsMonthly();
    }
}


?>