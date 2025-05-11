<?php
// Other Users Dashboard

session_start(); // Start session

// Redirect to login if other user not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>
    <style>
        body { font-family: Arial; background: #f4f4f4; margin: 0; padding: 40px; }
        .container { background: white; padding: 30px; max-width: 500px; margin: auto; margin-top: 80px; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); }
        h1 { margin-bottom: 20px; }
        label { display: block; margin-top: 15px; }
        input, select { width: 100%; padding: 10px; margin-top: 5px; border-radius: 4px; border: 1px solid #ccc; }
        button { margin-top: 20px; padding: 10px 20px; background: #007bff; border: none; color: white; border-radius: 4px; cursor: pointer; }
        button:hover { background: #0056b3; }
        .navbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background-color: #343a40;
            padding: 10px 20px;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 999;
            font-family: Arial;
        }
        .navbar-left, .navbar-right, .navbar-center {
            display: flex;
            align-items: center;
        }
        .navbar a {
            color: white;
            text-decoration: none;
            margin: 0 10px;
            font-weight: bold;
        }
        .navbar a:hover {
            text-decoration: underline;
        }
        .navbar-center span {
            color: white;
            font-weight: bold;
            font-style: italic;
            margin-left: 10px;
        }
        .dashboard-content {
            margin-top: 100px;
            text-align: center;
        }
    </style>
</head>
<body>
    <!-- Navigation Bar -->
    <div class="navbar">
        <div class="navbar-left">
            <!-- Add left-side links here if needed -->
        </div>
        <div class="navbar-center">
            <span>
                Welcome, <?php echo htmlspecialchars($_SESSION['username'] ?? 'Guest'); ?>
            </span>
        </div>
        <div class="navbar-right">
            <a href="/CSIT314/logout.php">Logout</a>
        </div>
    </div>
    <!-- Main Dashboard Content -->
    <div class="dashboard-content">
        <h2>
            Your Profile logged in as:
            <strong><?php echo htmlspecialchars($_SESSION['profileName'] ?? ''); ?></strong>
        </h2>
    </div>
</body>
</html>
