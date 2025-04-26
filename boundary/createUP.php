<?php
// Include necessary files
require_once(__DIR__ . '/../adminNavbar.php');
require_once(__DIR__ . '/../controllers/CreateUPController.php');

// Main processing logic for user account creation (acting as Controller)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collect form data with proper validation for 'profile'
    $data = [
        'name' => $_POST['name'],
        'is_suspended' => isset($_POST['is_suspended']) ? $_POST['is_suspended'] : false // Default to false if not set
    ];

    // Instantiate the CreateUserProfileController with validated data
    $controller = new CreateUserProfileController(new userProfile(null, $data['name'], $data['is_suspended']));

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
        
        <label for="name">Profile Name</label>
        <input type="text" id="name" name="name" required>

        <div class="form-actions">
            <button type="button" class="back-btn" onclick="location.href='/CSIT314/adminDashboard.php'">Back</button>
            <button type="submit">Create Profile</button>
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
