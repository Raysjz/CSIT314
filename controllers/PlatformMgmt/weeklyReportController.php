<?php
// Weekly Report Controller

// Include dependencies
require_once __DIR__ . '/../../entities/Shortlist.php';
require_once __DIR__ . '/../../entities/CleaningService.php';
require_once __DIR__ . '/../../entities/ServiceView.php';

// Controller for generating weekly platform reports
class WeeklyReportController
{
    // Get weekly shortlists added (non-deleted)
    public function getWeeklyShortlistsAdded()
    {
        return Shortlist::countShortlistsAddedWeekly();
    }

    // Get weekly new services created
    public function getWeeklyServicesCreated()
    {
        return CleaningService::countCreatedWeekly();
    }

    // Get weekly service views
    public function getWeeklyServiceViews()
    {
        return ServiceView::countViewsWeekly();
    }
}
?>
