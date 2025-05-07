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

if ($serviceId) {
    $controller = new ShortlistController();
    $added = $controller->addToShortlist($homeownerAccountId, $serviceId);

    $redirectUrl = "viewHO.php";
    if ($added) {
        header("Location: $redirectUrl?success=1");
    } else {
        header("Location: $redirectUrl?error=already_shortlisted");
    }
    exit();


} else {
    header('Location: viewHO.php');
    exit();
}
?>
