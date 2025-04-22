<?php
session_start();
/* Debug
echo '<pre>';
print_r($_SESSION);
echo '</pre>';
*/

if (!isset($_SESSION['user_id']) || $_SESSION['is_admin'] !== 'Yes') {
    echo "Access denied.";
    exit;
}
?>


<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard</title>
    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
        }
        .navbar {
            background-color: #333;
            overflow: hidden;
            padding: 10px 20px;
        }
        .navbar a {
            float: left;
            display: block;
            color: #f2f2f2;
            text-align: center;
            padding: 12px 16px;
            text-decoration: none;
        }
        .navbar a:hover {
            background-color: #575757;
        }
        .welcome {
            float: right;
            color: #f2f2f2;
            padding: 12px 16px;
        }
    </style>
</head>
<body>

<div class="navbar">
    <a href="view_accounts.php">View Accounts</a>
    <a href="CreateUAC.php">Create Account</a>
    <a href="create_profile.php">Create Profile</a>
    <a href="view_profiles.php">View Profiles</a>
    <a href="logout.php">Logout</a>
    <div class="welcome">Hi, <?php echo htmlspecialchars($_SESSION['username']); ?></div>
</div>

<!-- Your page content below -->
<h2 style="padding: 20px;">Welcome to the Admin Dashboard</h2>

</body>
</html>
