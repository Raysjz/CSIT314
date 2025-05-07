<?php
// suspendPC.php -> SuspendPlatformCategoryController.php -> PlatformCategory.php
session_start();
if ($_SESSION['profileName'] !== 'Platform Management') {
    header('Location: ../login.php');
    exit();
}
require_once(__DIR__ . '/platformNavbar.php');
require_once(__DIR__ . '/../../controllers/PlatformMgmt/SuspendPlatformCategoryController.php');

// Initialize message variable
$message = "";

// Get the category ID from the URL query parameter
$categoryIdToSuspend = isset($_GET['categoryid']) ? $_GET['categoryid'] : null;

// Instantiate the controller
$controller = new SuspendPlatformCategoryController();

// If the category ID is provided, suspend the category
if ($categoryIdToSuspend !== null) {
    $result = $controller->suspendPlatformCategory($categoryIdToSuspend);

    if ($result) {
        $message = "✅ Category with ID: " . htmlspecialchars($categoryIdToSuspend) . " has been successfully suspended!";
    } else {
        $message = "❌ Category not found or could not be suspended.";
    }
} else {
    $message = "❌ No category ID provided.";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Suspend Service Category</title>
    <style>
        body { font-family: Arial; background: #f4f4f4; margin: 0; padding: 40px; }
        .container { background: white; padding: 30px; max-width: 500px; margin: auto; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); position: relative; }
        h1 { margin-bottom: 20px; }
        .button-container { display: flex; justify-content: space-between; margin-top: 20px; }
        .back-button { padding: 10px 20px; border: none; color: white; border-radius: 4px; cursor: pointer; text-decoration: none; background: #6c757d; }
        .back-button:hover { background: #5a6268; }
    </style>
</head>
<body>
    <div class="container">
        <h1>Suspend Service Category</h1>
        <?php if (!empty($message)): ?>
            <div style="margin-bottom: 20px;"><?php echo htmlspecialchars($message); ?></div>
        <?php endif; ?>
        <div class="button-container">
            <a href="viewPC.php" class="back-button">Back</a>
        </div>
    </div>
</body>
</html>
