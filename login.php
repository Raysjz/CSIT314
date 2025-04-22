<?php
ob_start();
session_start();
// Enable error reporting
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Handle form submission
$login_error = '';


$conn = pg_connect("
    host=localhost
    port=5432
    dbname=csit314-database
    user=postgres
    password=1234
");

if (!$conn) {
    die("Database connection failed.");
}

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $result = pg_query_params($conn, "SELECT * FROM users WHERE username = $1", array($username));
    $user = pg_fetch_assoc($result);

    if ($user) {
        echo "<br>User ID: " . $user['id'] . "<br>";
        echo "Username: " . $user['username'] . "<br>";
        echo "Profile: " . $user['profile'] . "<br>";
        echo "Admin Status: " . $user['isuseradmin'] . "<br>";
    }
    if (password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['profile'] = $user['profile'];  
        $_SESSION['isAdmin'] = $user['isuseradmin'];
        
        // Debugging the session data
        var_dump($_SESSION);  // This will show the session data
        
        // Redirect based on user type (Admin or regular user)
        if ($user['isuseradmin'] === 'Yes') {
            header("Location: admin_dashboard.php");
            exit;
        }
         else {
            header("Location: user_dashboard.php");
            exit;
        }
    }
    
    
} 
ob_end_flush();
?>

<!DOCTYPE html>
<html lang="en">
            <body>
<form class="login-box" method="post" action="login.php">
                <h2>Welcome</h2>
                <?php if ($login_error): ?>
                    <div class="error"><?= htmlspecialchars($login_error) ?></div>
                <?php endif; ?>
                <input type="text" name="username" placeholder="Username" required><br>
                <input type="password" name="password" placeholder="Password" required><br>

                <label for="profile">User Profile:</label>
                <select name="profile" required>
                    <option value="">-- Select Profile --</option>
                    <option value="User Admin">User Admin</option>
                    <option value="Home Owner">Home Owner</option>
                    <option value="Cleaner">Cleaner</option>
                    <option value="Platform Management">Platform Management</option>
                </select><br>

                <input type="submit" value="Login">
            </form>



                </body>
<!--

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Group 07 Login</title>
    <style>
        body {
            margin: 0;
            font-family: 'Segoe UI', sans-serif;
            background-color: #f8f9fa;
        }

        .container {
            display: flex;
            height: 100vh;
        }

        .left-panel {
            flex: 2;
            background-color: #e0e0e0;
            display: flex;
            justify-content: center;
            align-items: center;
            border-radius: 0 40px 40px 0;
        }

        .left-panel h1 {
            font-size: 36px;
            text-align: center;
            line-height: 1.5;
            color: #333;
        }

        .right-panel {
            flex: 1;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .login-box {
            background-color: white;
            padding: 40px;
            border-radius: 20px;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
            text-align: center;
            width: 100%;
            max-width: 300px;
        }

        .login-box h2 {
            margin-bottom: 20px;
        }

        .login-box input[type="text"],
        .login-box input[type="password"],
        .login-box select {
            width: 90%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .login-box input[type="submit"] {
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            background-color: #333;
            color: white;
            cursor: pointer;
        }

        .login-box input[type="submit"]:hover {
            background-color: #555;
        }

        .error {
            color: red;
            font-size: 0.9em;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="left-panel">
            <h1>Group 07<br>Services..</h1>
        </div>
        <div class="right-panel">
            <form class="login-box" method="post" action="">
                <h2>Welcome</h2>
                <?php if ($login_error): ?>
                    <div class="error"><?= htmlspecialchars($login_error) ?></div>
                <?php endif; ?>
                <input type="text" name="username" placeholder="Username" required><br>
                <input type="password" name="password" placeholder="Password" required><br>

                <label for="profile">User Profile:</label>
                <select name="profile" required>
                    <option value="">-- Select Profile --</option>
                    <option value="User Admin">User Admin</option>
                    <option value="Home Owner">Home Owner</option>
                    <option value="Cleaner">Cleaner</option>
                    <option value="Platform Management">Platform Management</option>
                </select><br>

                <input type="submit" value="Login">
            </form>
        </div>
    </div>
</body>
</html>

--->
