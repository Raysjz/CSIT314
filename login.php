<?php
require_once(__DIR__ . '/controllers/loginController.php');
require_once(__DIR__ . '/controllers/UserProfileController.php');  // Include the UserProfileController
session_start();

// Instantiate the UserProfileController
$userProfileController = new UserProfileController();

// Fetch profiles for the dropdown
$profiles = $userProfileController->getProfiles();  // Get all profiles from the database

$login_error = '';


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // The controller will handle the login logic
    $controller = new UserAccountController();  // Correctly instantiate the controller
    $result = $controller->authenticate($_POST['username'], $_POST['password'], $_POST['profile_name']);

    if (isset($result['success']) && $result['success']) {
        // Handle success (redirect)
        $_SESSION['user_id'] = $result['user']['account_id'];
        $_SESSION['username'] = $result['user']['ua_username'];
        $_SESSION['profileId'] = $result['user']['profile_id'];
        $_SESSION['profileName'] = $result['user']['profile_name'];
        $_SESSION['isSuspended'] = $result['user']['is_suspended']; // Save the suspension status in session

        
        
        /*//Debug Echo the session values (for debugging or display purposes)  
        echo "User ID: " . $_SESSION['user_id'] . "<br>";
        echo "Username: " . $_SESSION['username'] . "<br>";
        echo "Profile ID: " . $_SESSION['profileId'] . "<br>";
        echo "Profile Name: " . $_SESSION['profileName'] . "<br>";
        echo "Suspended status from session: " . $_SESSION['isSuspended'] . "<br>";
        */

        // Redirect based on profile
        if($result['user']['profile_name'] === 'User Admin') {
            header("Location: /CSIT314/boundary/viewUA.php");
        }elseif($result['user']['profile_name'] === 'Homeowner'){
            header("Location: /CSIT314/boundary/viewHO.php");
        }elseif($result['user']['profile_name'] === 'Cleaner'){
            header("Location: /CSIT314/boundary/viewCS.php");
        }elseif($result['user']['profile_name'] === 'Platform Management'){
            header("Location: /CSIT314/boundary/PlatformMgmt/viewPC.php");
        }else {
            header("Location: /CSIT314/userDashboard.php");
        }
        exit;
        } else {
            // Show error if login failed
            $login_error = $result['error'];
        }
        
}


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

                <label for="profile">Profile</label>
                <select id="profile_id" name="profile_id" required onchange="updateProfileName()">
                <option value="">-- Select Profile --</option>
                <?php
                // Dynamically populate profile options from the database
                foreach ($profiles as $profile) {
                    echo "<option value='" . htmlspecialchars($profile['profile_id']) . "'>" . htmlspecialchars($profile['profile_name']) . "</option>";}?>
                </select>
                <input type="hidden" id="profile_name" name="profile_name">

                <input type="submit" value="Login">
            </form>
        </div>
        <script>
        function updateProfileName() {
            var profileSelect = document.getElementById('profile_id');
            var profileName = profileSelect.options[profileSelect.selectedIndex].text;
            document.getElementById('profile_name').value = profileName;
        }
        window.onload = updateProfileName;
        </script>

    </div>
</body>
</html>