<?php
// Suspend Service Category

session_start(); // Start session

// Redirect if not Platform Management
if ($_SESSION['profileName'] !== 'Platform Management') {
    header('Location: ../login.php');
    exit();
}

// Include dependencies
require_once __DIR__ . '/platformNavbar.php';
require_once __DIR__ . '/../../controllers/PlatformMgmt/SuspendScController.php';

// Initialize message variable
$message = "";

// Get the category ID from the URL query parameter
$categoryID = isset($_GET['categoryid']) ? $_GET['categoryid'] : null;

// Instantiate the controller
$controller = new SuspendServiceCategoryController();

// If the category ID is provided, suspend the category
if ($categoryID !== null) {
    $result = $controller->suspendServiceCategory($categoryID);

    // Fetch category for name if possible
    $category = $controller->getServiceCategoryById($categoryID);
    $categoryName = $category ? $category->getName() : null;

    if ($result) {
        if ($categoryName) {
            $message = "✅ Category ID: <strong>" . htmlspecialchars($categoryID) . "</strong> , " . htmlspecialchars($categoryName) . "</strong> has been successfully suspended!";
        } else {
            $message = "✅ Category ID: <strong>" . htmlspecialchars($categoryID) . "</strong> has been successfully suspended!";
        }
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
        body { font-family: Arial, sans-serif; background: #f4f4f4; margin: 0; padding: 40px; }
        .container { background: #fff; padding: 30px; max-width: 500px; margin: 80px auto 0; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); position: relative; }
        h1 { margin-bottom: 20px; }
        .button-container { display: flex; justify-content: space-between; margin-top: 20px; }
        .back-button { padding: 10px 20px; border: none; color: #fff; border-radius: 4px; cursor: pointer; text-decoration: none; background: #6c757d; }
        .back-button:hover { background: #5a6268; }
        .message { margin-bottom: 20px; padding: 10px; border-radius: 5px; text-align: center; font-weight: bold; }
        .success { background-color: #28a745; color: #fff; }
        .error { background-color: #dc3545; color: #fff; }
    </style>
</head>
<body>
    <div class="container">
        <h1>Suspend Service Category</h1>
        <?php if (!empty($message)): ?>
        <div class="message <?php echo (strpos($message, '❌') !== false) ? 'error' : 'success'; ?>">
            <?php echo $message; ?>
        </div>
    <?php endif; ?>
    <div class="button-container">
        <a href="viewSC.php" class="back-button">Back</a>
    </div>
</div>
