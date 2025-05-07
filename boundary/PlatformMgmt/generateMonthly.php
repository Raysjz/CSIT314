<?php
session_start();  // Start the session to access session variables
if ($_SESSION['profileName'] !== 'Platform Management') {
    header('Location: ../login.php');
    exit();
}
// Include necessary files
require_once(__DIR__ . '/platformNavbar.php');
require_once(__DIR__ . '/../../controllers/PlatformMgmt/monthlyReportController.php');

$shortlists = $monthlyViews = $monthly = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['generate_report'])) {
    $controller = new MonthlyReportController();
    $shortlists = $controller->getMonthlyShortlistsAdded();
    $monthlyViews = $controller->getMonthlyServiceViews();
    $monthly = $controller->getMonthlyServicesCreated();
}


?>

<!DOCTYPE html>
<html>
<head>
    <title>Generate Monthly Report Details</title>
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
    <h2>Generate Monthly Report Details</h2>
    <form method="post">
        <button type="submit" name="generate_report" class="generate-btn">Generate Report</button>
    </form>
    <?php if ($shortlists !== null && $monthlyViews !== null && $monthly !== null): ?>
    <div class="details-row"><span class="label">Shortlists added Monthly:</span>  <?php echo $shortlists; ?></div>
    <div class="details-row"><span class="label">Service Views Monthly: </span>  <?php echo $monthlyViews; ?></div>
    <div class="details-row"><span class="label">New Services Created Monthly:</span> <?php echo $monthly; ?></div>
    <?php endif; ?>
</div>
</body>
</html>
