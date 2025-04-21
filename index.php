<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head><title>Welcome</title></head>
<body>
<h2>Welcome, <?php echo $_SESSION['username']; ?>!</h2>
<p>You are logged in as: <strong><?php echo $_SESSION['profile']; ?></strong></p>
<a href="logout.php">Logout</a>
</body>
</html>
