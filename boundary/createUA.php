<?php
// Include necessary files
require_once(__DIR__ . '/../adminNavbar.php');
require_once(__DIR__ . '/../controllers/CreateUAController.php');
require_once(__DIR__ . '/../controllers/UserProfileController.php');  // Include the UserProfileController

// Instantiate the UserProfileController
$userProfileController = new UserProfileController();

// Fetch profiles for the dropdown
$profiles = $userProfileController->getProfiles();  // Get all profiles from the database

// Initialize message variable
$message = "";

// Main processing logic for user account creation (acting as Controller)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collect form data with proper validation for 'profile'
    $data = [
        'username' => $_POST['username'],
        'password' => $_POST['password'],
        'profile' => isset($_POST['profile']) ? $_POST['profile'] : null, // Don't use a default, just set null if not set
        'is_suspended' => isset($_POST['is_suspended']) ? $_POST['is_suspended'] : false // Default to false if not set
    ];

    // Check if the 'profile' field is set and not empty
    if (empty($data['profile'])) {
        echo "❌ Profile is required."; // Provide feedback if the profile is missing
        exit;
    }

    // Instantiate the CreateUserAccountController with validated data
    $controller = new CreateUserAccountController(new UserAccount(null, $data['username'], $data['password'], $data['profile'], $data['is_suspended']));

    // Call handleFormSubmission method to process and create the user account
    $result = $controller->handleFormSubmission($data);

    // Check the result and display an appropriate message
    if ($result === true) {
        $message = "✅ Profile successfully created!";
    } else {
        $message = "❌ Error creating profile: $result";
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
        .container { background: white; padding: 30px; max-width: 500px; margin: auto; margin-top: 80px; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); }
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

    <!-- Display success or error message -->
    <?php if ($message): ?>
        <div class="message <?php echo (strpos($message, '❌') !== false) ? 'error' : 'success'; ?>">
            <?php echo $message; ?>
        </div>
    <?php endif; ?>

    <form id="createForm" action="createUA.php" method="post" onsubmit="return handleFormSubmit(event)">
        
        <label for="username">Username</label>
        <input type="text" id="username" name="username" required>

        <label for="password">Password</label>
        <input type="password" id="password" name="password" required>

        <label for="profile">Profile</label>
        <select id="profile" name="profile" required>
            <option value="">-- Select Profile --</option>
            <?php
            // Dynamically populate profile options from the database
            foreach ($profiles as $profile) {
                echo "<option value='" . htmlspecialchars($profile['profile_id']) . "'>" . htmlspecialchars($profile['profile_name']) . "</option>";
            }
            ?>
        </select>

        <div class="button-container">
            <a href="/CSIT314/adminDashboard.php" class="back-button">Back</a>
            <button type="submit" class="update-button">Create Profile</button>
        </div>
    </form>
</div>

<script>
    function handleFormSubmit(event) {
        event.preventDefault(); // stop normal form submission
        document.getElementById('createForm').submit();
    }
</script>

</body>
</html>
