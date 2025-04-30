<?php
// Include necessary files
require_once(__DIR__ . '/../platNavbar.php');
require_once(__DIR__ . '/../controllers/SuspendScController.php');

// Initialize message variable
$message = "";

// Get the user ID from the URL query parameter
$userIdToSuspend = isset($_GET['userid']) ? $_GET['userid'] : null;

// Instantiate the controller
$controller = new suspendServiceCategoryController();

// If the user ID is provided, suspend the user
if ($userIdToSuspend !== null) {
    // Call the controller to suspend the user
    $result = $controller->suspendServiceCategory($userIdToSuspend);

    if ($result) {
        // Success message with user ID
        $message = "✅ User with ID: " . htmlspecialchars($userIdToSuspend) . " has been successfully suspended!";
    } else {
        $message = "❌ User not found or could not be suspended.";
    }
} else {
    $message = "❌ No user ID provided.";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Suspend User</title>
    <style>
        body { font-family: Arial; background: #f4f4f4; margin: 0; padding: 40px; }
        .container { background: white; padding: 30px; max-width: 500px; margin: auto; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); position: relative; }
        h1 { margin-bottom: 20px; }
        label { display: block; margin-top: 15px; }
        input, select { width: 100%; padding: 10px; margin-top: 5px; border-radius: 4px; border: 1px solid #ccc; }
        .button-container { display: flex; justify-content: space-between; margin-top: 20px; }
        .back-button, .update-button { padding: 10px 20px; border: none; color: white; border-radius: 4px; cursor: pointer; text-decoration: none; }
        .back-button { background: #6c757d; }
        .back-button:hover { background: #5a6268; }
        .update-button { background: #28a745; }
        .update-button:hover { background: #218838; }
    </style>
</head>
<body>
    <div class="container">
        <h1>Suspend Service Category</h1>
        <?php if (!empty($message)): ?>
            <div style="margin-bottom: 20px;"><?php echo htmlspecialchars($message); ?></div>
        <?php endif; ?>
        <div class="button-container">
            <a href="viewSC.php" class="back-button">Back</a>
        </div>
    </div>
</body>
</html>


