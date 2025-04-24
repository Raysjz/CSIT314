<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}


?>


<!DOCTYPE html>
<html>
<head><title>User Dashboard</title></head>
<body>
<h2>Welcome, <?php echo $_SESSION['username']; ?>!</h2>
<p>You are logged in as: <strong><?php echo $_SESSION['profile']; ?></strong></p>
<a href="logout.php">Logout</a>
</body>
</html>
