<?php
session_start();
if ($_SESSION['profileName'] !== 'User Admin') {
    header('Location: ../login.php');
    exit();
}
// Include necessary files
require_once(__DIR__ . '/adminNavbar.php');
require_once(__DIR__ . '/../../controllers/UserAdmin/UserProfileController.php');
require_once(__DIR__ . '/../../controllers/UserAdmin/CreateUAController.php');

// Fetch profiles for the dropdown
$userProfileController = new UserProfileController();
$profiles = $userProfileController->getProfiles();

// Initialize message variable
$message = "";

// Main processing logic for user account creation
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $data = [
        'username'     => $_POST['username'],
        'password'     => $_POST['password'],
        'fullname'     => $_POST['fullname'],
        'email'        => $_POST['email'],
        'profileName'  => $_POST['profile_name'],
        'profileId'    => $_POST['profile_id'],
        'isSuspended'  => isset($_POST['isSuspended']) ? $_POST['isSuspended'] : false
    ];

    if (empty($data['profileId'])) {
        $message = "❌ Profile is required.";
    } else {
        $controller = new CreateUserAccountController();
        $result = $controller->handleFormSubmission($data);

        if ($result === true) {
            $message = "✅ Profile successfully created!";
        } else {
            $message = "❌ Error creating profile: $result";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Create New Account</title>
    <style>
        body { font-family: Arial; background: #f4f4f4; margin: 0; padding: 40px; }
        .container { background: white; padding: 30px; width: 100%; margin-top: 80px; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); box-sizing: border-box; }
        h1 { margin-bottom: 20px; }
        label { display: block; margin-top: 15px; }
        input, select { width: 100%; padding: 10px; margin-top: 5px; border-radius: 4px; border: 1px solid #ccc; }
        .message { padding: 10px; margin: 20px 0; border-radius: 5px; text-align: center; }
        .success { background-color: #28a745; color: white; }
        .error { background-color: #dc3545; color: white; }
        .button-container { display: flex; justify-content: space-between; margin-top: 20px; }
        .back-button, .update-button { padding: 10px 20px; border: none; color: white; border-radius: 4px; cursor: pointer; text-decoration: none; }
        .back-button { background: #6c757d; }
        .back-button:hover { background: #5a6268; }
        .update-button { background: #28a745; }
        .update-button:hover { background: #218838; }
    </style>
</head>
<body>
<div class="container">
    <h1>Create Account</h1>
    <?php if ($message): ?>
        <div class="message <?php echo (strpos($message, '❌') !== false) ? 'error' : 'success'; ?>">
            <?php echo $message; ?>
        </div>
    <?php endif; ?>
    <form id="createForm" action="createUA.php" method="post">
        <label for="fullname"><b>Full Name</b></label>
        <input type="text" id="fullname" name="fullname" required>

        <label for="username">Username</label>
        <input type="text" id="username" name="username" required>

        <label for="email">Email</label>
        <input type="email" id="email" name="email" required>

        <label for="password">Password</label>
        <input type="password" id="password" name="password" required>

        <label for="profile_id">Profile</label>
        <select id="profile_id" name="profile_id" required onchange="updateProfileName()">
            <option value="">-- Select Profile --</option>
            <?php foreach ($profiles as $profile): ?>
                <option value="<?php echo htmlspecialchars($profile['profile_id']); ?>">
                    <?php echo htmlspecialchars($profile['profile_name']); ?>
                </option>
            <?php endforeach; ?>
        </select>
        <input type="hidden" id="profile_name" name="profile_name">

        <div class="button-container">
            <a href="viewUA.php" class="back-button">Back</a>
            <button type="submit" class="update-button">Create Profile</button>
        </div>
    </form>
</div>
<script>
function updateProfileName() {
    var profileSelect = document.getElementById('profile_id');
    var profileName = profileSelect.options[profileSelect.selectedIndex].text;
    document.getElementById('profile_name').value = profileName;
}
</script>
</body>
</html>
