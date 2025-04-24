<?php
session_start();

require_once(__DIR__ . '/entities/db.php');

//-------------------------------Start of Login Boundary--------------------------------

$login_error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // The controller will handle the login logic
    $controller = new LoginController();
    $result = $controller->authenticate($_POST['username'], $_POST['password'], $_POST['profile']);

    if (isset($result['success']) && $result['success']) {
        // Handle success (redirect)
        $_SESSION['user_id'] = $result['user']['id'];
        $_SESSION['username'] = $result['user']['username'];
        $_SESSION['profile'] = $result['user']['profile'];
        $_SESSION['is_suspended'] = $result['user']['issuspended'];

        // Redirect based on profile
        if ($result['user']['profile'] === 'User Admin') {
            header("Location: boundary/adminDashboard.php");
        } else {
            header("Location: userDashboard.php");
        }
        exit;
    } else {
        // Show error if login failed
        $login_error = $result['error'];
    }
}

//-------------------------------End of Login Boundary--------------------------------

//-------------------------------Start of Login Controller--------------------------------

class LoginController {
    public function authenticate($username, $password, $profile) {
        // Instantiate the LoginEntity to handle the actual authentication logic
        $loginEntity = new LoginEntity();  // Correct class name
        return $loginEntity->validateUser($username, $password, $profile);  // Correct method call
    }
}

//-------------------------------End of Login Controller--------------------------------

//-------------------------------Start of Login Entity-------------------------------------



class loginEntity {
    public function validateUser($username, $password, $profile) {
        $conn = Database::getPgConnect(); // Use pg_connect if that's your choice

        if (empty($username) || empty($profile)) {
            return ['error' => 'Username and profile are required.'];
        }

        // Query database to find user with username and profile
        $result = pg_query_params($conn, "SELECT * FROM useraccount WHERE username = $1 AND profile = $2", [$username, $profile]);
        $user = pg_fetch_assoc($result);

        if ($user) {
            if ($password === $user['password']) { // 🔐 Or use password_verify() if hashed
                // Return user details on success
                return [
                    'success' => true,
                    'user' => $user
                ];
            } else {
                return ['error' => '❌ Incorrect password.'];
            }
        } else {
            return ['error' => '❌ No user found with that username/profile.'];
        }
    }
}

//-------------------------------End of Login Entity-------------------------------------


?>

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