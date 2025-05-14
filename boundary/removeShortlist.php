<?php
session_start();
require_once('../controllers/ShortlistController.php');

$homeownerAccountId = $_SESSION['user_id'];
$serviceId = $_GET['id'] ?? null;

if ($serviceId) {
    $controller = new ShortlistController();
    $controller->removeFromShortlist($homeownerAccountId, $serviceId);
    header("Location: viewHOServiceDetails.php?id=$serviceId&removed=1");
    exit();
} else {
    header('Location: viewHO.php');
    exit();
}

?>