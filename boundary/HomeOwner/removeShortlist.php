<?php
// Remove from shortlist Details

session_start(); // Start session

// Redirect if not Homeowner
if ($_SESSION['profileName'] !== 'Homeowner') {
    header('Location: ../login.php');
    exit();
}


// Include dependencies
require_once(__DIR__ . '/../../controllers/ShortlistController.php');

$controller = new ShortlistController();

$homeownerAccountId = $_SESSION['user_id'] ?? null;
$shortlistId = $_GET['shortlist_id'] ?? null;
$serviceId = $_GET['id'] ?? null;

if ($shortlistId) {
    // Remove by shortlist_id (from shortlist page)
    $controller->removeByShortlistId($shortlistId);
    header("Location: viewHOshortlist.php?removed=1");
    exit();
} elseif ($serviceId && $homeownerAccountId) {
    // Remove by service_id (from service details page)
    $controller->removeFromShortlist($homeownerAccountId, $serviceId);
    header("Location: viewHO.php?id=$serviceId&removed=1");
    exit();
} else {
    // Fallback: redirect to main list
    header('Location: viewHO.php?error=invalid_request');
    exit();
}
?>
