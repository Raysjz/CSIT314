<?php
ob_start();
session_start();
// Enable error reporting
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
// Handle form submission
$login_error = '';
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';
    $profile  = trim($_POST['profile'] ?? '');

    echo " $username,$password,$profile";

    $expected_username = 'admin';
    $expected_password = '1234';
    $expected_profile  = 'User Admin';

    if ($username === $expected_username && $password === $expected_password) {
        if ($profile === $expected_profile) {
            $_SESSION['username'] = $username;
            $_SESSION['profile'] = $profile;

            header("Location: hardIndex.php"); // REDIRECT
            exit;
        } else {
            $login_error = "Incorrect profile selected for this user.";
        }
    } else {
        $login_error = "Invalid email or password.";
    }
}
ob_end_flush();
?>


<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <style>
        body {
            font-family: sans-serif;
            padding: 30px;
        }
        form {
            max-width: 300px;
            margin: auto;
        }
        label, select, input {
            display: block;
            margin: 10px 0;
            width: 100%;
        }
        input[type="submit"] {
            background-color: #4CAF50; 
            color: white;
            border: none;
            padding: 10px;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <h2>Login Form</h2>
    <form method="POST" action="hardlogin.php">
        <label for="username">Username:</label>
        <input type="text" name="username" required>

        <label for="password">Password:</label>
        <input type="password" name="password" required>

        <label for="profile">User Profile:</label>
        <select name="profile" required>
            <option value="">-- Select --</option>
            <option value="User Admin">User Admin</option>
            <option value="Home Owner">Home Owner</option>
            <option value="Cleaner">Cleaner</option>
            <option value="Platform Management">Platform Management</option>
        </select>

        <input type="submit" value="Login">
    </form>
</body>
</html>
