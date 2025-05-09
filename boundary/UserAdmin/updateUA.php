<?php
// Update User Account

session_start(); // Start session

// Redirect if not User Admin
if ($_SESSION['profileName'] !== 'User Admin') {
    header('Location: ../login.php');
    exit();
}

// Include dependencies
require_once __DIR__ . '/adminNavbar.php';
require_once __DIR__ . '/../../controllers/UserAdmin/UpdateUAController.php';
require_once __DIR__ . '/../../controllers/UserAdmin/UserProfileController.php';

// Instantiate controllers
$userProfileController = new UserProfileController();
$profiles = $userProfileController->getProfiles(); // Get all profiles

$userID = $_GET['userid'] ?? null;
$controller = new UpdateUserAccountController();
$userAccount = $controller->getAccountUserById($userID);
$message = "";

// Show error if user not found
if (!$userAccount) {
    echo "❌ No user found with ID: " . htmlspecialchars($userID);
    exit;
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $data = [
        'userid'       => $_POST['userid'],
        'username'     => $_POST['username'],
        'password'     => $_POST['password'],
        'fullname'     => $_POST['fullname'],
        'email'        => $_POST['email'],
        'profileName'  => $_POST['profile_name'],
        'profileId'    => $_POST['profile_id'],
        'isSuspended'  => isset($_POST['is_suspended']) ? $_POST['is_suspended'] : false
    ];

    $result = $controller->updateUserAccount($data);

    if ($result) {
        $message = "✅ Profile successfully updated!";
    } else {
        $message = "❌ Error updating profile.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>Update User</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f4f4;
            margin: 0;
            padding: 40px;
        }
        .container {
            background: #fff;
            padding: 30px;
            max-width: 500px;
            margin: 80px auto 0;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            position: relative;
        }
        h1 {
            margin-bottom: 20px;
            font-weight: normal;
        }
        label {
            display: block;
            margin-top: 15px;
            font-weight: bold;
        }
        input, select {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            border-radius: 4px;
            border: 1px solid #ccc;
            box-sizing: border-box;
        }
        .message { padding: 10px; margin: 20px 0; border-radius: 5px; text-align: center; }
        .success { background-color: #28a745; color: white; }
        .error { background-color: #dc3545; color: white; }
        .button-container {
            display: flex;
            justify-content: space-between;
            margin-top: 20px;
        }
        .back-button,
        .update-button {
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            color: #fff;
            cursor: pointer;
            text-decoration: none;
            font-size: 1rem;
        }
        .back-button {
            background: #6c757d;
        }
        .back-button:hover {
            background: #5a6268;
        }
        .update-button {
            background: #28a745;
        }
        .update-button:hover {
            background: #218838;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Update User</h1>
        <?php if ($message): ?>
        <div class="message <?php echo (strpos($message, '❌') !== false) ? 'error' : 'success'; ?>">
            <?php echo htmlspecialchars($message); ?>
        </div>
        <?php endif; ?>
        <form action="updateUA.php?userid=<?php echo htmlspecialchars($userAccount->getId()); ?>" method="post">
            <label for="userid">User ID</label>
            <input type="text" id="userid" name="userid" value="<?php echo htmlspecialchars($userAccount->getId()); ?>" readonly />

            <label for="fullname">Full Name</label>
            <input type="text" id="fullname" name="fullname" value="<?php echo htmlspecialchars($userAccount->getFullName()); ?>" required />

            <label for="username">Username</label>
            <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($userAccount->getUsername()); ?>" required />

            <label for="email">Email</label>
            <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($userAccount->getEmail()); ?>" required />

            <label for="password">Password</label>
            <input type="text" id="password" name="password" value="<?php echo htmlspecialchars($userAccount->getPassword()); ?>" required />

            <label for="profile_id">Profile</label>
            <select id="profile_id" name="profile_id" required onchange="updateProfileName()">
                <?php
                $currentProfile = $userAccount->getProfile();
                foreach ($profiles as $profile) {
                    $selected = ($profile['profile_name'] == $currentProfile) ? 'selected' : '';
                    echo "<option value='" . htmlspecialchars($profile['profile_id']) . "' $selected>" . htmlspecialchars($profile['profile_name']) . "</option>";
                }
                ?>
            </select>
            <input type="hidden" id="profile_name" name="profile_name" value="<?php echo htmlspecialchars($currentProfile); ?>" />

            <label for="is_suspended">Is Suspended</label>
            <select id="is_suspended" name="is_suspended">
                <option value="1" <?php echo $userAccount->getIsSuspended() ? 'selected' : ''; ?>>Yes</option>
                <option value="0" <?php echo !$userAccount->getIsSuspended() ? 'selected' : ''; ?>>No</option>
            </select>

            <div class="button-container">
                <a href="viewUA.php" class="back-button">Back</a>
                <button type="submit" class="update-button">Update Account</button>
            </div>
        </form>
        <script>
            // Update hidden profile_name field when profile changes
            function updateProfileName() {
                var profileSelect = document.getElementById('profile_id');
                var profileName = profileSelect.options[profileSelect.selectedIndex].text;
                document.getElementById('profile_name').value = profileName;
            }
            // Set profile name on page load
            window.onload = function() {
                updateProfileName();
            };
        </script>
    </div>
</body>
</html>
