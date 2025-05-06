<?php
session_start();
if ($_SESSION['profileName'] !== 'Homeowner') {
    header('Location: ../login.php');
    exit();
}

require_once(__DIR__ . '/../homeownerNavbar.php');
require_once(__DIR__ . '/../entities/CleaningServices.php'); // Your CleaningService class
require_once(__DIR__ . '/../controllers/UserAccountController.php');

// 1. Get the service_id from URL
$serviceId = isset($_GET['service_id']) ? (int)$_GET['service_id'] : null;
if (!$serviceId) {
    echo "<p>No service selected.</p>";
    exit;
}

// 2. Fetch the service object
$service = CleaningService::getCleaningServiceById($serviceId);
if (!$service) {
    echo "<p>Service not found.</p>";
    exit;
}

// 3. Get the cleaner's user ID from the service
$cleanerId = $service->getCleanerAccountId();

// 4. Fetch the cleaner's profile
$cleaner = CleaningService::getCleanerByServiceId($serviceId);
if (!$cleaner) {
    echo "<p>Cleaner not found.</p>";
    exit;
}

// 5. Fetch all services by this cleaner
$services = CleaningService::getServicesByCleaner($cleanerId);

?>
<!DOCTYPE html>
<html>
<head>
    <title>Cleaner Profile & Services</title>
    <style>
        .details-container { max-width: 900px; margin: 40px auto; background: #fff; padding: 30px; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); }
        .details-container h2 { margin-top: 0; }
        .details-row { margin-bottom: 18px; }
        .label { font-weight: bold; color: #333; }
        .back-button {
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            color: white;
            text-decoration: none;
            margin-right: 10px;
            cursor: pointer;
            background: #6c757d;
        }
        .back-button:hover { background: #5a6268; }
        .service-list { margin-top: 30px; }
        .service-table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        .service-table th, .service-table td { border: 1px solid #ddd; padding: 10px; text-align: left; }
        .service-table th { background-color: #f2f2f2; }
    </style>
</head>
<body>
<div class="details-container">
    <h2>Cleaner Profile</h2>
    <div class="details-row"><span class="label">Name:</span> <?php echo htmlspecialchars($cleaner->full_name ?? $cleaner->fullname ?? ''); ?></div>
    <div class="details-row"><span class="label">Email:</span> <?php echo htmlspecialchars($cleaner->email ?? ''); ?></div>
    <div style="margin-top:30px;">
        <a href="viewHO.php" class="back-button">Back to Services</a>
    </div>

    <div class="service-list">
        <h3>Services Offered by <?php echo htmlspecialchars($cleaner->name ?? $cleaner->fullname ?? ''); ?></h3>
        <table class="service-table">
            <thead>
                <tr>
                    <th>Service ID</th>
                    <th>Title</th>
                    <th>Category</th>
                    <th>Description</th>
                    <th>Price</th>
                    <th>Availability</th>
                </tr>
            </thead>
            <tbody>
            <?php
            if (empty($services)) {
                echo "<tr><td colspan='8' style='text-align:center; color:#888;'>No services found.</td></tr>";
            } else {
                foreach ($services as $svc) {
                    // For each service, get the cleaner's info (should be same for all, but for robustness)
                    $svcCleaner = CleaningService::getCleanerByServiceId($svc->getServiceId());
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($svc->getServiceId()) . "</td>";
                    echo "<td>" . htmlspecialchars($svc->getTitle()) . "</td>";
                    echo "<td>" . htmlspecialchars($svc->getCategoryName()) . "</td>";
                    echo "<td>" . htmlspecialchars($svc->getDescription()) . "</td>";
                    echo "<td>$" . htmlspecialchars(number_format($svc->getPrice(), 2)) . "</td>";
                    echo "<td>" . htmlspecialchars($svc->getAvailability()) . "</td>";
                    echo "</tr>";
                }
            }
            ?>
            </tbody>
        </table>
    </div>
</div>
</body>
</html>
