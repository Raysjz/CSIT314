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
require_once __DIR__ . '/../../controllers/PlatformMgmt/dailyReportController.php';

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
        body { font-family: Arial, sans-serif; background: #f4f4f4; margin: 0; padding: 40px; }
        .container { background: #fff; padding: 30px; max-width: 500px; margin: 80px auto 0; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); }
        h1 { margin-bottom: 20px; }
        label { display: block; margin-top: 15px; font-weight: bold; }
        input, select { width: 100%; padding: 10px; margin-top: 5px; border-radius: 4px; border: 1px solid #ccc; box-sizing: border-box; }
        .message { padding: 10px; margin: 20px 0; border-radius: 5px; text-align: center; font-weight: bold; }
        .success { background-color: #28a745; color: white; }
        .error { background-color: #dc3545; color: white; }
        .button-container { display: flex; justify-content: space-between; margin-top: 20px; }
        .back-button, .update-button { padding: 10px 20px; border: none; color: white; border-radius: 4px; cursor: pointer; text-decoration: none; }
        .back-button { background: #6c757d; }
        .back-button:hover { background: #5a6268; }
        .update-button { background: #28a745; }
        .update-button:hover { background: #218838; }
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
