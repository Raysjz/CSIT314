<?php
// Suspend Cleaning Service

session_start(); // Start session

if ($_SESSION['profileName'] !== 'Cleaner') {
    header('Location: ../login.php');
    exit();
}

// Include dependencies
require_once __DIR__ . '/cleanerNavbar.php';
require_once __DIR__ . '/../../controllers/Cleaner/SuspendCSController.php';


// Get service ID from query
$serviceID = isset($_GET['serviceid']) ? $_GET['serviceid'] : null;

// Instantiate controller
$controller = new SuspendCleaningServiceController();
$message = "";

if ($serviceID !== null) {
    $result = $controller->suspendCleaningService($serviceID);
    if ($result) {
        $service = $controller->getCleaningServiceById($serviceID);
        $name = $service ? $service->getTitle() : '';
        $message = "✅ Service ID: <strong>" . htmlspecialchars($serviceID) . "</strong> , " . htmlspecialchars($name) . " has been successfully suspended!";
    } else {
        $message = "❌ Service not found or could not be suspended.";
    }
} else {
    $message = "❌ No service ID provided.";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Suspend Cleaning Service</title>
    <style>
        body { font-family: Arial; background: #f4f4f4; margin: 0; padding: 40px; }
        .container { background: white; padding: 30px; max-width: 500px; margin: auto; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); }
        h1 { margin-bottom: 20px; }
        .button-container { display: flex; justify-content: space-between; margin-top: 20px; }
        .back-button { padding: 10px 20px; border: none; color: white; border-radius: 4px; cursor: pointer; background: #6c757d; text-decoration: none; }
        .back-button:hover { background: #5a6268; }
        .message {
            margin-bottom: 20px;
            padding: 10px;
            border-radius: 5px;
            text-align: center;
            font-weight: bold;
            background: #dc3545;
            color: #fff;
        }
        .message.success {
            background: #28a745;
        }
        .error {
            background-color: #dc3545;
            color: #fff;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Suspend Cleaning Service</h1>
        <?php if (!empty($message)): ?>
        <div class="message <?php echo (strpos($message, '❌') !== false) ? 'error' : 'success'; ?>">
            <?php echo $message; ?>
        </div>
        <?php endif; ?>
        <div class="button-container">
            <a href="viewCS.php" class="back-button">Back</a>
        </div>
    </div>
</body>
</html>
