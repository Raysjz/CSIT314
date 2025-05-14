<?php
session_start();  // Start the session to access session variables
if ($_SESSION['profileName'] !== 'User Admin') {
    header('Location: ../login.php');
    exit();
}
// Include necessary files
require_once(__DIR__ . '/adminNavbar.php');
require_once(__DIR__ . '/../controllers/UpdateUAController.php');
require_once(__DIR__ . '/../controllers/UserProfileController.php');

// Instantiate the UserProfileController
$userProfileController = new UserProfileController();

// Fetch profiles for the dropdown
$profiles = $userProfileController->getProfiles();  // Get all profiles from the database

// Get the user ID from the query parameter
$userIdToUpdate = isset($_GET['userid']) ? $_GET['userid'] : null;

// Instantiate the Controller for fetching and updating user account data
$controller = new UpdateUserAccountController();
$userToUpdate = $controller->getAccountUserById($userIdToUpdate);

// If no user is found, show an error message
if (!$userToUpdate) {
    echo "❌ No user found with ID: " . htmlspecialchars($userIdToUpdate);
    exit;
}

// Handle form submission for updating user data
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collect form data
    $data = [
        'userid' => $_POST['userid'],
        'username' => $_POST['username'],
        'password' => $_POST['password'],
        'fullname' => $_POST['fullname'],
        'email'    => $_POST['email'],
        'profileName' => $_POST['profile_name'],
        'profileId' => $_POST['profile_id'],
        'isSuspended' => isset($_POST['is_suspended']) ? $_POST['is_suspended'] : false
    ];
    
    
    // Call controller to update user account
    $result = $controller->updateUserAccount($data);

    if ($result === true) {
        // Success - redirect to the user list page after a delay
        echo "<script>
        alert('✅ User account has been successfully updated!');
        setTimeout(function() {
            window.location.href = 'viewUA.php'; // Redirect to the user list page
        }, 500);
        </script>";
        exit;
    } else {
        echo "<script>alert('❌ Error updating user account: $result');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
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

            <label for="fullname">Full Name</label>
             <input type="text" id="fullname" name="fullname" value="<?php echo htmlspecialchars($userToUpdate->getFullName()); ?>" required>

            <label for="username">Username</label>
            <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($userToUpdate->getUsername()); ?>" required>

            <label for="email">Email</label>
            <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($userToUpdate->getEmail()); ?>" required>

            <label for="password">Password</label>
            <input type="text" id="password" name="password" value="<?php echo htmlspecialchars($userToUpdate->getPassword()); ?>" required>

            <label for="profile">Profile</label>
            <select id="profile_id" name="profile_id" required onchange="updateProfileName()">
                <?php
                // Assuming $userToUpdate contains the current user profile data
                $currentProfile = $userToUpdate->getProfile();  // The user's current profile

                // Dynamically populate profile options from the database
                foreach ($profiles as $profile) {
                    // Check if the current user's profile matches the profile from the database
                    $selected = ($profile['profile_name'] == $currentProfile) ? 'selected' : ''; // Set 'selected' if it matches the profile retrieved from database
                    echo "<option value='" . htmlspecialchars($profile['profile_id']) . "' $selected>" . htmlspecialchars($profile['profile_name']) . "</option>";
                }
                ?>
            </select>
            <input type="hidden" id="profile_name" name="profile_name" value="<?php echo htmlspecialchars($currentProfile); ?>">

            <label for="is_suspended">Is Suspended</label>
            <select id="is_suspended" name="is_suspended">
                <option value="1" <?php echo $userToUpdate->getIsSuspended() ? 'selected' : ''; ?>>Yes</option>
                <option value="0" <?php echo !$userToUpdate->getIsSuspended() ? 'selected' : ''; ?>>No</option>
            </select>

            <div class="button-container">
                <a href="viewUA.php" class="back-button">Back</a>
                <button type="submit" class="update-button">Update Account</button>
            </div>
        </form>
        <script>
                // JavaScript to update the hidden field with the profile name when a profile is selected
                function updateProfileName() {
                    var profileSelect = document.getElementById('profile_id');
                    var profileName = profileSelect.options[profileSelect.selectedIndex].text;
                    console.log('Selected Profile Name:', profileName);  // Debugging
                    document.getElementById('profile_name').value = profileName;
                }

                // Call the function once on page load to ensure that the correct profile name is set
                window.onload = function() {
                    updateProfileName();  // Sets the profile name when the page is loaded
                };
        </script>

    </div>
</body>
</html>
