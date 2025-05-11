<?php
// Service View Controller

// Include dependencies
require_once(__DIR__ . '/../entities/ServiceView.php');

class ServiceViewController {
    public function logView($serviceId, $viewerAccountId = null) {
        ServiceView::logView($serviceId, $viewerAccountId);
    }

    public function getViewCount($serviceId) {
        return ServiceView::countViews($serviceId);
    }

}
?>