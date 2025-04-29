<?php
require_once(__DIR__ . '/adminNavbar.php');

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
            <style>
            body { font-family: Arial; background: #f4f4f4; margin: 0; padding: 40px; }
            .container { background: white; padding: 30px; max-width: 500px; margin: auto; margin-top: 80px; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); }
            h1 { margin-bottom: 20px; }
            label { display: block; margin-top: 15px; }
            input, select { width: 100%; padding: 10px; margin-top: 5px; border-radius: 4px; border: 1px solid #ccc; }
            button { margin-top: 20px; padding: 10px 20px; background: #007bff; border: none; color: white; border-radius: 4px; cursor: pointer; }
            button:hover { background: #0056b3; }
        </style>
    </head>
    <body>
        <br><br>
        <h2>Welcome, <?php echo $_SESSION['username']; ?> to the Admin Dashboard !</h2>
        <p>You are logged in as: <strong><?php echo $_SESSION['profile']; ?></strong></p>
    </body>
</html>


