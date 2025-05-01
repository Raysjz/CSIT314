<?php
session_start();  // Start the session to access session variables
if ($_SESSION['profileName'] !== 'Homeowner') {
    header('Location: ../login.php');
    exit();
}
require_once(__DIR__ . '/../homeownerNavbar.php');
require_once(__DIR__ . '/..//controllers/ViewHOServiceController.php');
require_once(__DIR__ . '/../controllers/PlatformCategoryController.php');
require_once(__DIR__ . '/../controllers/UserAccountController.php');
require_once(__DIR__ . '/../controllers/ShortlistController.php');

$serviceId = $_GET['id'] ?? null;
$controller = new ViewHOServiceController();
list($service, $cleaner) = $controller->getServiceAndCleaner($serviceId);

if (!$service) {
    echo "<p>Service not found.</p>";
    exit;
}

$categoryName = method_exists($service, 'getCategoryName') ? $service->getCategoryName() : '';





$message = "";
if (isset($_GET['success']) && $_GET['success'] == 1) {
    $message = "✅ Service successfully added to your shortlist!";
}
if (isset($_GET['error']) && $_GET['error'] === 'already_shortlisted') {
    $message = "⚠️ This service is already in your shortlist.";
}
if (isset($_GET['removed']) && $_GET['removed'] == 1) {
    $message = "✅ Service removed from your shortlist.";
}

$homeownerAccountId = $_SESSION['user_id']; // or $_SESSION['accountId'], use your session variable
$shortlistController = new ShortlistController();
$shortlistedServices = $shortlistController->getShortlistedServices($homeownerAccountId);
$shortlistedIds = array_map(function($svc) {
    return is_object($svc) ? $svc->service_id : $svc['service_id'];
}, $shortlistedServices);
$isShortlisted = in_array($service->getServiceId(), $shortlistedIds);

// Build an array of shortlisted service IDs for easy lookup
$shortlistedIds = array_map(function($svc) {
    return is_object($svc) ? $svc->service_id : $svc['service_id'];
}, $shortlistedServices);

$isShortlisted = in_array($service->getServiceId(), $shortlistedIds);



?>
<!DOCTYPE html>
<html>
<head>
    <title>Service Details</title>
    <style>
        .details-container { max-width: 600px; margin: 40px auto; background: #fff; padding: 30px; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); }
        .details-container h2 { margin-top: 0; }
        .details-row { margin-bottom: 18px; }
        .label { font-weight: bold; color: #333; }
        .message {
            padding: 10px;
            margin: 20px 0;
            border-radius: 5px;
            text-align: center;
        }
        .success {
            background-color: #28a745;  /* Green background for success */
            color: white;
        }
        .error {
            background-color: #ffc107;  /* Yellow background for warning */
            color: #856404;
        }
        .shortlist-button, .remove-button, .back-button {
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            color: white;
            text-decoration: none;
            margin-right: 10px;
            cursor: pointer;
        }
        .shortlist-button { background: #007bff; }
        .shortlist-button:hover { background: #0056b3; }
        .remove-button { background: #dc3545; }
        .remove-button:hover { background: #c82333; }
        .back-button { background: #6c757d; }
        .back-button:hover { background: #5a6268; }

        .book-button {
            background: #ff9800;        /* Bright orange */
            color: white;
            padding: 10px 24px;
            border: none;
            border-radius: 4px;
            text-decoration: none;
            margin-right: 10px;
            font-weight: bold;
            font-size: 16px;
            cursor: pointer;
            box-shadow: 0 2px 8px rgba(255,152,0,0.1);
            transition: background 0.2s, box-shadow 0.2s;
        }
        .book-button:hover {
            background: #fb8c00;        /* Slightly darker on hover */
            box-shadow: 0 4px 16px rgba(255,152,0,0.2);
            text-decoration: none;
        }

    </style>
</head>
<body>
<div class="details-container">
    <?php if ($message): ?>
        <div class="message <?php echo (strpos($message, 'successfully') !== false || strpos($message, 'removed') !== false) ? 'success' : 'error'; ?>">
            <?php echo $message; ?>
        </div>
    <?php endif; ?>
    <h2><?php echo htmlspecialchars($service->getTitle()); ?></h2>
    <div class="details-row"><span class="label">Category:</span> <?php echo htmlspecialchars($categoryName); ?></div>
    <div class="details-row"><span class="label">Price:</span> $<?php echo number_format($service->getPrice(),2); ?></div>
    <div class="details-row"><span class="label">Availability:</span> <?php echo htmlspecialchars($service->getAvailability()); ?></div>
    <div class="details-row"><span class="label">Description:</span> <?php echo htmlspecialchars($service->getDescription()); ?></div>
    <div class="details-row"><span class="label">Contact:</span> [Contact information available after booking]</div>
    <div style="margin-top:30px;">
        <a href="viewHOshortlist.php" class="back-button">Back to List</a>
        <a href="#" class="book-button">Book</a>
        <?php if ($isShortlisted): ?>
            <a href="removeShortlist.php?id=<?php echo $service->getServiceId(); ?>" class="remove-button">Remove from Shortlist</a>
        <?php else: ?>
            <a href="addShortlist.php?id=<?php echo $service->getServiceId(); ?>" class="shortlist-button">Add to Shortlist</a>
        <?php endif; ?>


    </div>
</div>
</body>
</html>
