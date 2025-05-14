<?php
// Dummy data for users (replace with your actual data source)
$users = [
    ['userid' => 1, 'full_name' => 'John Doe', 'username' => 'johndoe', 'email' => 'john@example.com', 'role' => 'User Admin'],
    ['userid' => 2, 'full_name' => 'Jane Smith', 'username' => 'janesmith', 'email' => 'jane@example.com', 'role' => 'Home Owner'],
    ['userid' => 3, 'full_name' => 'Peter Jones', 'username' => 'peterj', 'email' => 'peter@example.com', 'role' => 'Cleaner'],
    ['userid' => 4, 'full_name' => 'Alice Brown', 'username' => 'aliceb', 'email' => 'alice@example.com', 'role' => 'Platform Management'],
    ['userid' => 5, 'full_name' => 'Bob Williams', 'username' => 'bobwill', 'email' => 'bob@example.com', 'role' => 'User Admin'],
    ['userid' => 6, 'full_name' => 'Charlie Davis', 'username' => 'charlied', 'email' => 'charlie@example.com', 'role' => 'Home Owner'],
    ['userid' => 7, 'full_name' => 'Diana Evans', 'username' => 'dianae', 'email' => 'diana@example.com', 'role' => 'Cleaner'],
    ['userid' => 8, 'full_name' => 'Ethan Foster', 'username' => 'ethanf', 'email' => 'ethan@example.com', 'role' => 'Platform Management'],
];

// Retrieve the user ID from the query parameter
$userIdToUpdate = isset($_GET['userid']) ? $_GET['userid'] : null;
$userToUpdate = null;

// Find the user based on the ID
if ($userIdToUpdate !== null) {
    foreach ($users as $user) {
        if ($user['userid'] == $userIdToUpdate) {
            $userToUpdate = $user;
            break;
        }
    }
}

// If no user is found, you might want to redirect or display an error
if (!$userToUpdate) {
    // For now, let's just set a default empty user
    $userToUpdate = ['userid' => '', 'full_name' => '', 'username' => '', 'email' => '', 'role' => 'user'];
}
?>

<!DOCTYPE html>
<html>
<head>
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

<div class="Navbar">
    <div class="navbar-left">
        <a href="/CSIT314/boundary/viewUA.php">View Accounts</a>
        <a href="/CSIT314/boundary/createUA.php">Create Account</a> 
        <a href="/CSIT314/boundary/viewUP.php">View Profiles</a>
        <a href="/CSIT314/boundary/createUP.php">Create Profile</a> 
    </div>
    <div class="navbar-right">
        <text color ="white" >Welcome, <?php echo isset($_SESSION['username']) ? $_SESSION['username'] : 'Guest'; ?> </text>
        <a href="/CSIT314/logout.php">Logout</a>
    </div>
</div>

<style>
    .navbar {
        display: flex;
        justify-content: space-between;
        align-items: center;
        background-color: #343a40;
        padding: 10px 20px;
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        z-index: 999;
    }

    .navbar a {
        color: white;
        text-decoration: none;
        margin: 0 10px;
        font-weight: bold;
    }

    .navbar a:hover {
        text-decoration: underline;
    }

    .navbar-left, .navbar-right {
        display: flex;
        align-items: center;
    }

    .navbar-right text {
    color: white;
    }

</style>

    <div class="container">
        <h1>Update User</h1>
        <form action="update_user_process.php" method="post">
            <label for="userid">User ID</label>
            <input type="text" id="userid" name="userid" value="<?php echo htmlspecialchars($userToUpdate['userid']); ?>" readonly>

            <label for="full_name">Full Name</label>
            <input type="text" id="full_name" name="full_name" value="<?php echo htmlspecialchars($userToUpdate['full_name']); ?>" required>

            <label for="username">Username</label>
            <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($userToUpdate['username']); ?>" readonly>

            <label for="email">Email</label>
            <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($userToUpdate['email']); ?>" required>

            <label for="role">Role</label>
            <select id="role" name="role" required>
                <option value="User Admin" <?php if ($userToUpdate['role'] === 'User Admin') echo 'selected'; ?>>User Admin</option>
                <option value="Home Owner" <?php if ($userToUpdate['role'] === 'Home Owner') echo 'selected'; ?>>Home Owner</option>
                <option value="Cleaner" <?php if ($userToUpdate['role'] === 'Cleaner') echo 'selected'; ?>>Cleaner</option>
                <option value="Platform Management" <?php if ($userToUpdate['role'] === 'Platform Management') echo 'selected'; ?>>Platform Management</option>
            </select>

            <div class="button-container">
                <a href="view_user.php" class="back-button">Back</a>
                <button type="submit" class="update-button">Update Account</button>
            </div>
        </form>
    </div>
</body>
</html>