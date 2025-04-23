<?php
// Dummy data for users (replace with your actual data source)
$allUsers = [
    ['userid' => 1, 'full_name' => 'John Doe', 'username' => 'johndoe', 'email' => 'john@example.com', 'role' => 'User Admin'],
    ['userid' => 2, 'full_name' => 'Jane Smith', 'username' => 'janesmith', 'email' => 'jane@example.com', 'role' => 'Home Owner'],
    ['userid' => 3, 'full_name' => 'Peter Jones', 'username' => 'peterj', 'email' => 'peter@example.com', 'role' => 'Cleaner'],
    ['userid' => 4, 'full_name' => 'Alice Brown', 'username' => 'aliceb', 'email' => 'alice@example.com', 'role' => 'Platform Management'],
    ['userid' => 5, 'full_name' => 'Bob Williams', 'username' => 'bobwill', 'email' => 'bob@example.com', 'role' => 'User Admin'],
    ['userid' => 6, 'full_name' => 'Charlie Davis', 'username' => 'charlied', 'email' => 'charlie@example.com', 'role' => 'Home Owner'],
    ['userid' => 7, 'full_name' => 'Diana Evans', 'username' => 'dianae', 'email' => 'diana@example.com', 'role' => 'Cleaner'],
    ['userid' => 8, 'full_name' => 'Ethan Foster', 'username' => 'ethanf', 'email' => 'ethan@example.com', 'role' => 'Platform Management'],
];

$filteredUsers = $allUsers; // Initialize with all users
$searchTerm = '';

if (isset($_GET['search'])) {
    $searchTerm = strtolower(trim($_GET['search']));
    $filteredUsers = array_filter($allUsers, function ($user) use ($searchTerm) {
        return (
            strpos(strtolower($user['username']), $searchTerm) !== false ||
            strpos(strtolower($user['userid']), $searchTerm) !== false
        );
    });
}

// Dummy user data for the displayed details (using the first user in the filtered list if available)
$user = !empty($filteredUsers) ? $filteredUsers[0] : ['username' => '', 'email' => '', 'role' => ''];
?>

<!DOCTYPE html>
<html>
<head>
    <title>View User</title>
    <style>
        body { font-family: Arial; background: #f4f4f4; margin: 0; padding: 40px; }
        .container { background: white; padding: 30px; max-width: 1000px; margin: auto; margin-top: 80px; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); }
        h1 { margin-bottom: 20px; }
        .detail { margin-bottom: 15px; }
        .label { font-weight: bold; }
        .search-container { margin-bottom: 20px; text-align: center; }
        .search-container h2 { margin-top: 0; }
        .search-input { padding: 10px; border: 1px solid #ddd; border-radius: 4px; width: 60%; margin-bottom: 10px; }
        .search-button { padding: 10px 20px; background-color: #007bff; color: white; border: none; border-radius: 4px; cursor: pointer; }
        .search-button:hover { background-color: #0056b3; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 10px; text-align: left; }
        th { background-color: #f2f2f2; font-weight: bold; }
        .actions-buttons button { padding: 8px 12px; margin-right: 5px; border: none; border-radius: 4px; cursor: pointer; }
        .actions-buttons .update-button { background-color: #28a745; color: white; text-decoration: none; }
        .actions-buttons .suspend-button { background-color: #dc3545; color: white; }
        .actions-buttons button:hover { opacity: 0.8; }
        .no-results { text-align: center; font-style: italic; color: #777; }
    </style>
</head>
<body>

<?php require 'navbar.php'; ?>

    <div class="container">

        <div class="search-container">
            <h2>Search by username or id</h2>
            <form action="" method="GET">
                <input type="text" class="search-input" name="search" placeholder="Enter username or user ID" value="<?php echo htmlspecialchars($searchTerm); ?>">
                <button type="submit" class="search-button">Search</button>
            </form>
        </div>

        <h2>User List</h2>
        <?php if (!empty($filteredUsers)): ?>
            <table>
                <thead>
                    <tr>
                        <th>User ID</th>
                        <th>Full Name</th>
                        <th>Username</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($filteredUsers as $user): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($user['userid']); ?></td>
                            <td><?php echo htmlspecialchars($user['full_name']); ?></td>
                            <td><?php echo htmlspecialchars($user['username']); ?></td>
                            <td><?php echo htmlspecialchars($user['email']); ?></td>
                            <td><?php echo htmlspecialchars(ucfirst($user['role'])); ?></td>
                            <td class="actions-buttons">
                            <button onclick="window.location.href='update_user.php?userid=<?php echo htmlspecialchars($user['userid']); ?>';" class="update-button">Update</button>
                                <button class="suspend-button">Suspend</button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p class="no-results">No users found matching your search criteria.</p>
        <?php endif; ?>
    </div>
</body>
</html>