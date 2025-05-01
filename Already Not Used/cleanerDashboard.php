<?php
session_start();  // Start the session to access session variables
if ($_SESSION['profileName'] !== 'Cleaner') {
    header('Location: ../login.php');
    exit();
}

require_once(__DIR__ . '/cleanerNavbar.php');

?>

<!DOCTYPE html>
<html>
    <head>
        <title>Dashboard</title>
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
        <h2>Welcome, <?php echo $_SESSION['username']; ?> to the Dashboard !</h2>
        <p>You are logged in as: <strong><?php echo $_SESSION['profileName']; ?></strong></p>
        <a href="/CSIT314/logout.php">Logout</a>
    </body>
</html>

