<?php
// Daily Platform Report

session_start(); // Start session

// Redirect if not Platform Management
if ($_SESSION['profileName'] !== 'Platform Management') {
    header('Location: ../login.php');
    exit();
}

// Include dependencies
require_once __DIR__ . '/platformNavbar.php';
require_once __DIR__ . '/../../controllers/ServiceCategory/dailyReportController.php';

// Initialize variables
$shortlists = $dailyViews = $daily = null;

// Generate report on button click
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['generate_report'])) {
    $controller = new DailyReportController();
    $shortlists = $controller->getDailyShortlistsAdded();
    $dailyViews = $controller->getDailyServiceViews();
    $daily = $controller->getDailyServicesCreated();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Daily Platform Report</title>
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
    <h2>Generate Daily Report</h2>
    <form method="post">
        <button type="submit" name="generate_report" class="generate-btn">Generate Report</button>
    </form>
    <?php if ($shortlists !== null && $dailyViews !== null && $daily !== null): ?>
        <div class="details-row"><span class="label">Shortlists Added Today:</span> <?php echo htmlspecialchars($shortlists); ?></div>
        <div class="details-row"><span class="label">Service Views Today:</span> <?php echo htmlspecialchars($dailyViews); ?></div>
        <div class="details-row"><span class="label">New Services Created Today:</span> <?php echo htmlspecialchars($daily); ?></div>
    <?php endif; ?>
</div>
</body>
</html>
