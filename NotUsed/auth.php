<?php
session_start();

$conn = pg_connect("
    host=localhost
    port=5432
    dbname=csit314-database
    user=postgres
    password=1234
");

if (!$conn) {
    die("Connection failed.");
}

$username = $_POST['username'];
$password = $_POST['password'];

// Query the user
$result = pg_query_params($conn, "SELECT * FROM users WHERE username = $1", array($username));
$user = pg_fetch_assoc($result);

if ($user) {
    // Check if suspended
    if ($user['issuspended'] === 'true') {
        echo "Account is suspended.";
        exit;
    }

    // Check password — assuming it's hashed
    if (password_verify($password, $user['password'])) {
        // Set session variables
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['profile'] = $user['profile'];
        $_SESSION['isAdmin'] = $user['isuseradmin'];

        // Redirect based on profile
        if ($user['isuseradmin'] === 'true') {
            header("Location: dashboard.php");
        } else {
            header("Location: dashboard.php");
        }
        exit;
    } else {
        echo "Invalid password.";
    }
} else {
    echo "User not found.";
}
?>
