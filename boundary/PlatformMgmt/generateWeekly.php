<?php
// Weekly Platform Report

session_start(); // Start session

// Redirect if not Platform Management
if ($_SESSION['profileName'] !== 'Platform Management') {
    header('Location: ../login.php');
    exit();
}

// Include dependencies
require_once __DIR__ . '/platformNavbar.php';
require_once __DIR__ . '/../../controllers/PlatformMgmt/weeklyReportController.php';

// Initialize variables for report results
$shortlists = $weeklyViews = $weekly = null;

// Only generate report if button is clicked
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['generate_report'])) {
    $controller = new WeeklyReportController();
    $shortlists = $controller->getWeeklyShortlistsAdded();
    $weeklyViews = $controller->getWeeklyServiceViews();
    $weekly = $controller->getWeeklyServicesCreated();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Generate Weekly Report Details</title>
    <style>
        .details-container { max-width: 600px; margin: 40px auto; background: #fff; padding: 30px; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); }
        .details-container h2 { margin-top: 0; }
        .details-row { margin-bottom: 18px; }
        .label { font-weight: bold; color: #333; }
        .generate-btn { padding: 10px 24px; background: #007bff; color: #fff; border: none; border-radius: 4px; cursor: pointer; }
        .generate-btn:hover { background: #0056b3; }
    </style>
</head>
<body>
<div class="details-container">
    <h2>Generate Weekly Report Details</h2>
    <form method="post">
        <button type="submit" name="generate_report" class="generate-btn">Generate Report</button>
    </form>
    <?php if ($shortlists !== null && $weeklyViews !== null && $weekly !== null): ?>
        <div class="details-row"><span class="label">Shortlists added Weekly:</span>  <?php echo htmlspecialchars($shortlists); ?></div>
        <div class="details-row"><span class="label">Service Views Weekly:</span>  <?php echo htmlspecialchars($weeklyViews); ?></div>
        <div class="details-row"><span class="label">New Services Created Weekly:</span> <?php echo htmlspecialchars($weekly); ?></div>
    <?php endif; ?>
</div>
</body>
</html>
