<?php
// Update User Profile

session_start(); // Start session

// Redirect if not User Admin
if ($_SESSION['profileName'] !== 'User Admin') {
    header('Location: ../login.php');
    exit();
}

// Include dependencies
require_once __DIR__ . '/adminNavbar.php';
require_once __DIR__ . '/../../controllers/UserAdmin/UpdateUPController.php';

// Get the user ID from the query parameter
$userID = isset($_GET['userid']) ? $_GET['userid'] : null;

$controller = new UpdateUserProfileController();
$userProfile = $controller->getUserProfileById($userID);
$message = "";

// Show error if user not found
if (!$userProfile) {
    echo "❌ No user found with ID: " . htmlspecialchars($userID);
    exit;
}

// Handle form submission for updating user data
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $data = [
        'id' => $_POST['id'],
        'name' => $_POST['name'],
        // Convert to boolean: "1" => true, "0" => false
        'isSuspended' => isset($_POST['is_suspended']) && $_POST['is_suspended'] == "1"
    ];

    $result = $controller->updateUserProfile($data);

    $message = $result
        ? "✅ Profile successfully updated!"
        : "❌ Error updating profile.";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Update Profile</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f4f4f4; margin: 0; padding: 40px; }
        .container { background: #fff; padding: 30px; max-width: 500px; margin: 80px auto 0; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); }
        h1 { margin-bottom: 20px; font-weight: normal; }
        label { display: block; margin-top: 15px; font-weight: bold; }
        input, select { width: 100%; padding: 10px; margin-top: 5px; border-radius: 4px; border: 1px solid #ccc; box-sizing: border-box; }
        .message { padding: 10px; margin: 20px 0; border-radius: 5px; text-align: center; font-weight: bold; }
        .success { background-color: #28a745; color: #fff; }
        .error { background-color: #dc3545; color: #fff; }
        .button-container { display: flex; justify-content: space-between; margin-top: 20px; }
        .back-button, .update-button { padding: 10px 20px; border: none; color: #fff; border-radius: 4px; cursor: pointer; text-decoration: none; font-size: 1rem; }
        .back-button { background: #6c757d; }
        .back-button:hover { background: #5a6268; }
        .update-button { background: #28a745; }
        .update-button:hover { background: #218838; }
    </style>
</head>
<body>
<div class="container">
    <h1>Update Profile</h1>
    <?php if ($message): ?>
        <div class="message <?php echo (strpos($message, '❌') !== false) ? 'error' : 'success'; ?>">
            <?php echo htmlspecialchars($message); ?>
        </div>
    <?php endif; ?>
    <form action="updateUP.php?userid=<?php echo htmlspecialchars($userProfile->getProfileId()); ?>" method="post">
        <input type="hidden" name="id" value="<?php echo htmlspecialchars($userProfile->getProfileId()); ?>">
        <label for="name">Profile Name</label>
        <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($userProfile->getName()); ?>" required>
        <label for="is_suspended">Is Suspended</label>
        <select id="is_suspended" name="is_suspended">
            <option value="1" <?php echo $userProfile->getIsSuspended() ? 'selected' : ''; ?>>Yes</option>
            <option value="0" <?php echo !$userProfile->getIsSuspended() ? 'selected' : ''; ?>>No</option>
        </select>
        <div class="button-container">
            <a href="viewUP.php" class="back-button">Back</a>
            <button type="submit" class="update-button">Update Profile</button>
        </div>
    </form>
</div>
</body>
</html>
