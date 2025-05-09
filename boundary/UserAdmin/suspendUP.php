<?php
session_start();
if ($_SESSION['profileName'] !== 'User Admin') {
    header('Location: ../login.php');
    exit();
}
require_once(__DIR__ . '/adminNavbar.php');
require_once(__DIR__ . '/../../controllers/UserAdmin/suspendUPController.php');

$userIdToSuspend = isset($_GET['userid']) ? $_GET['userid'] : null;
$controller = new SuspendUserProfileController();

if ($userIdToSuspend !== null) {
    $result = $controller->suspendUserProfile($userIdToSuspend);

    if ($result) {
        $_SESSION['success_message'] = "✅ User with ID: " . htmlspecialchars($userIdToSuspend) . " has been successfully suspended!";
        header("Location: viewUP.php");
        exit;
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
    <title>Suspend User</title>
    <style>
        body { font-family: Arial; background: #f4f4f4; margin: 0; padding: 40px; }
        .container { background: white; padding: 30px; max-width: 500px; margin: auto; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); position: relative; }
        h1 { margin-bottom: 20px; }
        .message { padding: 10px; margin-bottom: 20px; border-radius: 5px; text-align: center; }
        .success { background-color: #28a745; color: white; }
        .error { background-color: #dc3545; color: white; }
        .button-container { display: flex; justify-content: space-between; margin-top: 20px; }
        .back-button { background: #6c757d; color: white; padding: 10px 20px; border: none; border-radius: 4px; cursor: pointer; text-decoration: none; }
        .back-button:hover { background: #5a6268; }
    </style>
</head>
<body>
<div class="container">
    <h1>Suspend User</h1>
    <?php if (!empty($message)): ?>
        <div class="message error"><?php echo htmlspecialchars($message); ?></div>
    <?php endif; ?>
    <div class="button-container">
        <a href="viewUP.php" class="back-button">Back</a>
    </div>
</div>
</body>
</html>
