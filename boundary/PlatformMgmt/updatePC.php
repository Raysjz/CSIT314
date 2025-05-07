<?php
session_start();  // Start the session to access session variables
if ($_SESSION['profileName'] !== 'Platform Management') {
    header('Location: ../login.php');
    exit();
}
// Include necessary files
require_once(__DIR__ . '/platNavbar.php');
require_once(__DIR__ . '/../../controllers/PlatformMgmt/UpdatePCController.php');

// Get the user ID from the query parameter
$userIdToUpdate = isset($_GET['userid']) ? $_GET['userid'] : null;
//echo "User ID to update: " . htmlspecialchars($userIdToUpdate) . "<br>";


// Instantiate the Controller for fetching and updating user profile data
$controller = new UpdatePlatformCategoryController(); 
$userToUpdate = $controller->getPlatformCategoryById($userIdToUpdate);

// If no user is found, show an error message
if (!$userToUpdate) {
    echo "❌ No user found with ID: " . htmlspecialchars($userIdToUpdate);
    exit;  // Stop the script if no user is found
}

// Initialize message variable
$message = "";

// Handle form submission for updating user data
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collect form data and sanitize it
    $data = [
        'id' => $_POST['id'],
        'name' => $_POST['name'],
        'isSuspended' => isset($_POST['is_suspended']) ? $_POST['is_suspended'] : false
    ];

    // Call controller to update user profile
    $result = $controller->updatePlatformCategory($data);

    // Show appropriate message based on the result
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
    <meta charset="UTF-8">
    <title>Update Profile</title>
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
    <h1>Update Service Category</h1>

    <!-- Display success or error message -->
    <?php if ($message): ?>
        <div class="message <?php echo (strpos($message, '❌') !== false) ? 'error' : 'success'; ?>">
            <?php echo $message; ?>
        </div>
    <?php endif; ?>

    <!-- Update form -->
    <form action="updatePC.php?userid=<?php echo htmlspecialchars($userToUpdate->getId()); ?>" method="post">
        <input type="hidden" name="id" value="<?php echo htmlspecialchars($userToUpdate->getId()); ?>">

        <label for="name">Service Category Name</label>
        <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($userToUpdate->getName()); ?>" required>

        <label for="is_suspended">Is Suspended</label>
        <select id="is_suspended" name="is_suspended">
            <option value="1" <?php echo $userToUpdate->getIsSuspended() ? 'selected' : ''; ?>>Yes</option>
            <option value="0" <?php echo !$userToUpdate->getIsSuspended() ? 'selected' : ''; ?>>No</option>
        </select>

        <div class="button-container">
            <a href="viewPC.php" class="back-button">Back</a>
            <button type="submit" class="update-button">Update Profile</button>
        </div>
    </form>
</div>

</body>
</html>
