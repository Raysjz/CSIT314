<?php
// Daily Report Controller

// Include dependencies
require_once __DIR__ . '/../../entities/Shortlist.php';
require_once __DIR__ . '/../../entities/CleaningService.php';
require_once __DIR__ . '/../../entities/ServiceView.php';

/**
 * Controller for generating daily platform reports
 */
class DailyReportController
{
    // Get daily shortlists added (non-deleted)
    public function getDailyShortlistsAdded()
    {
        return Shortlist::countShortlistsAddedDaily();
    }

    // Get daily new services created
    public function getDailyServicesCreated()
    {
        return CleaningService::countCreatedDaily();
    }

    // Get daily service views
    public function getDailyServiceViews()
    {
        return ServiceView::countViewsDaily();
    }
}
?>
