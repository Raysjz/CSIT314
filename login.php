<?php
session_start();

// Handle form submission
$login_error = '';


require 'db.php';

// Debug: Check if form submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    echo "<pre>";
    print_r($_POST);  // Debug: Print submitted form data
    echo "</pre>";

    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';
    $profile = $_POST['profile'] ?? '';

    /* Debug
    // You can add further checks or echo values
    echo "[POST]Username: $username<br>";
    echo "[POST]Password: $password<br>";
    echo "[POST]Profile: $profile<br>";
    */
}

    // Only query if username and profile are not empty
    if (!empty($username) && !empty($profile)) {
        $result = pg_query_params($conn, "SELECT * FROM users WHERE username = $1 AND profile = $2", array($username, $profile));
        $user = pg_fetch_assoc($result);

        if ($user) {
            // Optional: handle password match
            if ($password === $user['password']) {
                // Set session for each 
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['profile'] = $user['profile'];
                $_SESSION['is_admin'] = $user['isuseradmin'];
                $_SESSION['is_suspended'] = $user['issuspended'];

                // Redirect based on admin
                if ($user['isuseradmin'] === 'Yes') {
                    header("Location: admin_dashboard.php");
                } else {
                    header("Location: user_dashboard.php");
                }
                exit;
            } else {
                $login_error = '❌ Incorrect password.';
            }
        } else {
            $login_error = '❌ No user found with that username/profile.';
        }
    }

?>

<!--
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

    -->
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

            