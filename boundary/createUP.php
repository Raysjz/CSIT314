<?php
session_start();  // Start the session to access session variables
if ($_SESSION['profileName'] !== 'User Admin') {
    header('Location: ../login.php');
    exit();
}
// Include necessary files
require_once(__DIR__ . '/adminNavbar.php');
require_once(__DIR__ . '/../controllers/CreateUPController.php');

// Initialize message variable
$message = "";

// Main processing logic for user profile creation
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $data = [
        'name' => $_POST['name'],
        'isSuspended' => isset($_POST['isSuspended']) ? $_POST['isSuspended'] : false // Default to false if not set
    ];

    // Instantiate the controller with validated data
    $controller = new CreateUserProfileController(new userProfile(null, $data['name'], $data['isSuspended']));

    // Call handleFormSubmission method to process and create the user profile
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
    <title>Create New Profile</title>
    <style>
        body { font-family: Arial; background: #f4f4f4; margin: 0; padding: 40px; }
        .container { background: white; padding: 30px; max-width: 500px; margin: auto; margin-top: 80px; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); }
        h1 { margin-bottom: 20px; }
        label { display: block; margin-top: 15px; }
        input, select { width: 100%; padding: 10px; margin-top: 5px; border-radius: 4px; border: 1px solid #ccc; }
        .message {
            padding: 10px;
            margin: 20px 0;
            border-radius: 5px;
            text-align: center;
        }
        .success {
            background-color: #28a745;  /* Green background for success */
            color: white;
        }
        .error {
            background-color: #dc3545;  /* Red background for error */
            color: white;
        }
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
    <h1>Create Profile</h1>

    <!-- Display message if available -->
    <?php if ($message): ?>
        <div class="message <?php echo (strpos($message, '❌') !== false) ? 'error' : 'success'; ?>">
            <?php echo $message; ?>
        </div>
    <?php endif; ?>

    <form id="createForm" action="createUP.php" method="post">
        
        <label for="name">Profile Name</label>
        <input type="text" id="name" name="name" required>

        <div class="button-container">
            <a href="viewUP.php" class="back-button">Back</a>
            <button type="submit" class="update-button">Create Profile</button>
        </div>
    </form>
</div>

</body>
</html>
