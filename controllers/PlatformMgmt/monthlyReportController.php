<?php
require_once(__DIR__ . '/../../entities/Shortlist.php');
require_once(__DIR__ . '/../../entities/CleaningService.php');
require_once(__DIR__ . '/../../entities/ServiceView.php');

/**
 * Controller for generating monthly platform reports.
 */
class MonthlyReportController {
    /**
     * Get shortlists added this month.
     */
    public function getMonthlyShortlistsAdded() {
        return Shortlist::countShortlistsAddedMonthly();
    }

    /**
     * Get new services created this month.
     */
    public function getMonthlyServicesCreated() {
        return CleaningService::countCreatedMonthly();
    }

    /**
     * Get service views this month.
     */
    public function getMonthlyServiceViews() {
        return ServiceView::countViewsMonthly();
    }
}
?>
