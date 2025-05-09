<?php
session_start();
if ($_SESSION['profileName'] !== 'Homeowner') {
    header('Location: ../login.php');
    exit();
}
// Include dependencies
require_once __DIR__ . '/homeownerNavbar.php';
require_once __DIR__ . '/../../controllers/ShortlistController.php';
require_once(__DIR__ . '/../../controllers/ServiceViewController.php');
require_once __DIR__ . '/../../controllers/HomeOwner/viewHOshortlistDetailsController.php';

$homeownerAccountId = $_SESSION['user_id'];
$shortlistId = isset($_GET['id']) ? (int)$_GET['id'] : null;

// 1. Fetch shortlist entry by shortlist_id
$shortlistEntry = Shortlist::getById($shortlistId);

// 2. Security check: does this entry belong to the current user?
if (!$shortlistEntry || $shortlistEntry->homeowner_account_id != $homeownerAccountId) {
    echo "<p>Shortlist entry not found or access denied.</p>";
    exit;
}

// 3. Fetch service details using service_id from shortlist entry
$serviceId = $shortlistEntry->service_id;
$service = CleaningService::getCleaningServiceById($serviceId);

if (!$service) {
    echo "<p>Service not found.</p>";
    exit;
}

$categoryName = method_exists($service, 'getCategoryName') ? $service->getCategoryName() : '';

//------------------------- Service View Controller
$viewerAccountId = $_SESSION['user_id'] ?? null;

$viewController = new ServiceViewController();

// Only log once per session per service
$viewedKey = 'viewed_service_' . $serviceId;
if (empty($_SESSION[$viewedKey])) {
    $viewController->logView($serviceId, $viewerAccountId);
    $_SESSION[$viewedKey] = true;
}
//-----------------------------------------------------

    //----------------------------------------Short List Controller
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
    //----------------------------------------------------------------

// For the details page, you can check if this service is in the user's shortlist
$isShortlisted = true; // Since this page is for a specific shortlist entry

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
            background-color: #28a745;
            color: white;
        }
        .error {
            background-color: #ffc107;
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
        <?php if ($isShortlisted): ?>
            <a href="removeShortlist.php?id=<?php echo $service->getServiceId(); ?>" class="remove-button">Remove from Shortlist</a>
        <?php else: ?>
            <a href="addShortlist.php?id=<?php echo $service->getServiceId(); ?>" class="shortlist-button">Add to Shortlist</a>
        <?php endif; ?>
    </div>
</div>
</body>
</html>
