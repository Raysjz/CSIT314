<?php
session_start();

if (isset($_GET['id'])) {
    $serviceId = intval($_GET['id']);
    if (isset($_SESSION['shortlist'][$serviceId])) {
        unset($_SESSION['shortlist'][$serviceId]);
    }
}

header("Location: shortlist.php");
exit();
