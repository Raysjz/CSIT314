<?php
session_start();
require_once(__DIR__ . '/../controllers/ShortlistController.php');

// Check if user is logged in and is a Homeowner
if (!isset($_SESSION['user_id']) || $_SESSION['profileName'] !== 'Homeowner') {
    header('Location: ../login.php');
    exit();
}

$homeownerAccountId = $_SESSION['user_id'];
$serviceId = $_GET['id'] ?? null;
$action = $_GET['action'] ?? null;

if ($serviceId && in_array($action, ['add', 'remove'])) {
    $controller = new ShortlistController();

    if ($action === 'add') {
        $added = $controller->addToShortlist($homeownerAccountId, $serviceId);
        $redirectUrl = "viewHOServiceDetails.php?id=$serviceId";
        if ($added) {
            header("Location: $redirectUrl&success=1");
        } else {
            header("Location: $redirectUrl&error=already_shortlisted");
        }
        exit();
    } elseif ($action === 'remove') {
        $controller->removeFromShortlist($homeownerAccountId, $serviceId);
        header("Location: viewHOServiceDetails.php?id=$serviceId&removed=1");
        exit();
    }
} else {
    header('Location: viewHO.php');
    exit();
}
?>
