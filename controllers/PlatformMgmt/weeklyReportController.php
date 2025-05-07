<?php
require_once(__DIR__ . '/../../entities/Shortlist.php');
require_once(__DIR__ . '/../../entities/CleaningService.php');
require_once(__DIR__ . '/../../entities/ServiceView.php');

/**
 * Controller for generating weekly platform reports.
 */
class WeeklyReportController {
    /**
     * Get weekly shortlists added (non-deleted).
     * returns int
     */
    public function getWeeklyShortlistsAdded() {
        return Shortlist::countShortlistsAddedWeekly();
    }

    /**
     * Get weekly new services created.
     * returns int
     */
    public function getWeeklyServicesCreated() {
        return CleaningService::countCreatedWeekly();
    }

    /**
     * Get weekly service views.
     * returns int
     */
    public function getWeeklyServiceViews() {
        return ServiceView::countViewsWeekly();
    }
}
?>
