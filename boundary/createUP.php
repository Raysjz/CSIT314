<?php
// Include necessary files
require_once(__DIR__ . '/../adminNavbar.php');
require_once(__DIR__ . '/../controllers/CreateUPController.php');

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
        echo "<script>alert('Profile successfully created!');</script>";
    } else {
        echo "<script>alert('Error creating Profile: $result');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Create New Profile</title>
    <style>
        body { font-family: Arial; background: #f4f4f4; margin: 0; padding: 40px; }
        .container { background: white; padding: 30px; max-width: 500px; margin: auto; margin-top: 80px; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); }
        h1 { margin-bottom: 20px; }
        label { display: block; margin-top: 15px; }
        input, select { width: 100%; padding: 10px; margin-top: 5px; border-radius: 4px; border: 1px solid #ccc; }
        button { margin-top: 20px; padding: 10px 20px; background: #007bff; border: none; color: white; border-radius: 4px; cursor: pointer; }
        button:hover { background: #0056b3; }
    </style>
</head>
<body>

<div class="container">
    <h1>Create Profile</h1>
    <form id="createForm" action="createUP.php" method="post" onsubmit="return handleFormSubmit(event)">
        
        <label for="username">Username</label>
        <input type="text" id="username" name="username" required>

        <label for="password">Password</label>
        <input type="password" id="password" name="password" required>

        <label for="profile">Profile</label>
        <select id="profile" name="profile" required>
            <option value="">-- Select Profile --</option>
            <option value="User Admin">User Admin</option>
            <option value="Cleaner">Cleaner</option>
            <option value="Home Owner">Home Owner</option>
            <option value="Platform Management">Platform Management</option>
        </select>

        <div class="form-actions">
            <button type="button" class="back-btn" onclick="location.href='/CSIT314/boundary/adminDashboard.php'">Back</button>
            <button type="submit">Create Account</button>
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
