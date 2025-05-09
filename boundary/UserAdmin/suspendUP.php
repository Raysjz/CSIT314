<?php
// Suspend User Profile

session_start(); // Start session

// Redirect if not User Admin
if ($_SESSION['profileName'] !== 'User Admin') {
    header('Location: ../login.php');
    exit();
}

// Include dependencies
require_once __DIR__ . '/adminNavbar.php';
require_once __DIR__ . '/../../controllers/UserAdmin/suspendUPController.php';

// Get user ID from query
$userID = isset($_GET['userid']) ? $_GET['userid'] : null;

// Instantiate controller
$controller = new SuspendUserProfileController();
$message = "";

// Handle suspension logic
if ($userID !== null) {
    $result = $controller->suspendUserProfile($userID);

    if ($result) {
        // Fetch user to display name if possible
        $user = $controller->getUserProfileById($userID);
        if ($user) {
            $name = $user->getName();
            $message = "✅ User Profile ID: <strong>" . htmlspecialchars($userID) . "</strong> , " . htmlspecialchars($name) . " has been successfully suspended!";
        } else {
            $message = "✅ User Profile ID: " . htmlspecialchars($userID) . " has been successfully suspended!";
        }
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
    <meta charset="UTF-8" />
    <title>Suspend User</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f4f4;
            margin: 0;
            padding: 40px;
        }
        .container {
            background: #fff;
            padding: 30px;
            max-width: 500px;
            margin: 80px auto 0;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        h1 {
            margin-bottom: 20px;
            font-weight: normal;
        }
        .button-container {
            display: flex;
            justify-content: space-between;
            margin-top: 20px;
        }
        .back-button {
            background: #6c757d;
            color: #fff;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            text-decoration: none;
        }
        .back-button:hover {
            background: #5a6268;
        }
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
    <h1>Suspend User</h1>
    <?php if (!empty($message)): ?>
        <div class="message<?php echo (strpos($message, '✅') !== false) ? ' success' : ''; ?>">
            <?php echo $message; ?>
        </div>
    <?php endif; ?>
    <div class="button-container">
        <a href="viewUP.php" class="back-button">Back</a>
    </div>
</div>
</body>
</html>
