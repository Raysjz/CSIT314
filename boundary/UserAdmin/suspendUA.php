<?php
// Suspend User Account

session_start(); // Start session

// Redirect if not User Admin
if ($_SESSION['profileName'] !== 'User Admin') {
    header('Location: ../login.php');
    exit();
}

// Include dependencies
require_once __DIR__ . '/adminNavbar.php';
require_once __DIR__ . '/../../controllers/UserAdmin/suspendUAController.php';

// Get user ID from query
$userID = $_GET['userid'] ?? null;

// Instantiate controller
$controller = new SuspendUserAccountController();
$message = "";

// Handle suspension logic
if ($userID !== null) {
    $result = $controller->suspendUserAccount($userID);

    if ($result) {
        // Fetch user to display username if possible
        $user = $controller->getAccountUserById($userID);
        if ($user) {
            $username = $user->getUsername();
            $message = "✅ User Account ID: <strong>" . htmlspecialchars($userID) . "</strong> , " . htmlspecialchars($username) . " has been successfully suspended!";
        } else {
            $message = "✅ User Account ID: " . htmlspecialchars($userID) . " has been successfully suspended!";
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
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
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
            justify-content: flex-end;
            margin-top: 20px;
        }
        .back-button {
            padding: 10px 20px;
            border: none;
            color: #fff;
            border-radius: 4px;
            cursor: pointer;
            text-decoration: none;
            background: #6c757d;
            font-size: 1rem;
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
        <div class="message <?php echo (strpos($message, '❌') !== false) ? 'error' : 'success'; ?>">
            <?php echo $message; ?>
        </div>
        <?php endif; ?>
        <div class="button-container">
            <a href="viewUA.php" class="back-button">Back</a>
        </div>
    </div>
</body>
</html>
