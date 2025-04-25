<?php
// Include necessary files
require_once(__DIR__ . '/../boundary/adminNavbar.php');
require_once(__DIR__ . '/../entities/UserAccount.php');

/// Retrieve the user ID from the query parameter
$userIdToUpdate = isset($_GET['userid']) ? $_GET['userid'] : null;

// If a user ID is provided
if ($userIdToUpdate !== null) {
    // Fetch the user based on the ID
    $userToUpdate = UserAccount::getUserById($userIdToUpdate);  // Pass the ID as an argument
}

// If no user is found, show an error message
if (!$userToUpdate) {
    echo "❌ No user found with ID: " . htmlspecialchars($userIdToUpdate);
    exit;
}

// Handle form submission (update user)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get updated user data from form
    $updatedUser = new UserAccount(
        $_POST['userid'],  // user ID
        $_POST['username'],  // updated username
        $_POST['password'],  // updated password
        $_POST['profile'],   // updated profile
        $userToUpdate->getIsSuspended()  // retain the current suspended status (or update if needed)
    );

    // Save updated user to the database
    $result = $updatedUser->updateUserAccount();  // Assuming updateUserAccount() updates the user in the database

    // Check the result and display an appropriate message
    if ($result === true) {
        echo "<script>
        alert('✅ User account has been successfully updated!');
        // Delay redirection by 1 second (1000 milliseconds)
        setTimeout(function() {
            window.location.href = 'viewUA.php'; // Redirect to the user list page
        }, 500);
      </script>";
exit; // Ensure no further code is executed
    } else {
        echo "<script>alert('❌Error updating user account: $result');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update User</title>
    <style>
        body { font-family: Arial; background: #f4f4f4; margin: 0; padding: 40px; }
        .container { background: white; padding: 30px; max-width: 500px; margin: auto; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); position: relative; }
        h1 { margin-bottom: 20px; }
        label { display: block; margin-top: 15px; }
        input, select { width: 100%; padding: 10px; margin-top: 5px; border-radius: 4px; border: 1px solid #ccc; }
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
        <h1>Update User</h1>
        <form action="updateUA.php?userid=<?php echo htmlspecialchars($userToUpdate->getId()); ?>" method="post" onsubmit="return handleFormSubmit(event)">
            <label for="userid">User ID</label>
            <input type="text" id="userid" name="userid" value="<?php echo htmlspecialchars($userToUpdate->getId()); ?>" readonly>

            <label for="username">Username</label>
            <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($userToUpdate->getUsername()); ?>" required>

            <label for="password">Password</label>
            <input type="text" id="password" name="password" value="<?php echo htmlspecialchars($userToUpdate->getPassword()); ?>" required>

            <label for="profile">Profile</label>
            <select id="profile" name="profile" required>
                <option value="User Admin" <?php if ($userToUpdate->getProfile() === 'User Admin') echo 'selected'; ?>>User Admin</option>
                <option value="Home Owner" <?php if ($userToUpdate->getProfile() === 'Home Owner') echo 'selected'; ?>>Home Owner</option>
                <option value="Cleaner" <?php if ($userToUpdate->getProfile() === 'Cleaner') echo 'selected'; ?>>Cleaner</option>
                <option value="Platform Management" <?php if ($userToUpdate->getProfile() === 'Platform Management') echo 'selected'; ?>>Platform Management</option>
            </select>

            <div class="button-container">
                <a href="viewUA.php" class="back-button">Back</a>
                <button type="submit" class="update-button">Update Account</button>
            </div>
        </form>
    </div>
</body>
</html>
