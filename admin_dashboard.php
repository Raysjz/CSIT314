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
<div class="navbar">
    <div class="navbar-left">
        <a href="ViewUA.php">View Account</a>
        <a href="ViewUP.php">View Profile</a>
        <a href="CreateUA.php">Create Account</a> 
        <a href="CreateUP.php">Create Profile</a> 
    </div>
    <div class="navbar-right">
        <a href="logout.php">Logout</a>
    </div>
</div>
<style>
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

    .navbar-left, .navbar-right {
        display: flex;
        align-items: center;
    }
</style>
</head>
<body>


<!-- Your page content below -->

<h2>Welcome, <?php echo $_SESSION['username']; ?> to the Admin Dashboard !</h2>
<p>You are logged in as: <strong><?php echo $_SESSION['profile']; ?></strong></p>

</body>
</html>


