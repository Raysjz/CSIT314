<?php
require_once('adminNavbar.php'); // Include the navbar file
session_start();

/* Debug
echo '<pre>';
print_r($_SESSION);
echo '</pre>';
*/

// Check if the profile is set and if the user is an admin
if (!isset($_SESSION['profile']) || $_SESSION['profile'] !== 'User Admin') {
    echo "Access denied.";
    exit;
}



?>

<!DOCTYPE html>
<html>
    <head>
        <title>Admin Dashboard</title>
    </head>
    <body>
        <br><br>
        <h2>Welcome, <?php echo $_SESSION['username']; ?> to the Admin Dashboard !</h2>
        <p>You are logged in as: <strong><?php echo $_SESSION['profile']; ?></strong></p>
    </body>
</html>


