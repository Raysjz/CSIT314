<?php
// View User Profiles

session_start(); // Start session

// Redirect if not User Admin
if ($_SESSION["profileName"] !== "User Admin") {
    header("Location: ../login.php");
    exit();
}

// Include dependencies
require_once __DIR__ . "/adminNavbar.php";
require_once __DIR__ . "/../../controllers/UserAdmin/viewUPController.php";
require_once __DIR__ . "/../../controllers/UserAdmin/searchUPController.php";

// Pagination parameters
$perPage = 10;
$page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
$offset = ($page - 1) * $perPage;
$searchQuery = isset($_GET["search"]) ? trim($_GET["search"]) : null;

// Instantiate controllers
$viewController = new ViewUserProfileController();
$searchController = new SearchUserProfileController();

// Fetch user profiles (search or all)
if ($searchQuery) {
    $result = $searchController->searchUserProfiles($searchQuery, $perPage, $offset);
} else {
    $result = $viewController->viewUserProfiles($perPage, $offset);
}
$userProfiles = $result['data'];
$total = $result['total'];
$totalPages = ceil($total / $perPage);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View User Profiles</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f4f4;
            margin: 0;
            padding: 10px;
        }
        .container {
            background: #fff;
            padding: 24px;
            width: 100%;
            max-width: 800px;
            margin: 20px auto 0 auto;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            box-sizing: border-box;
        }
        h1, h2 {
            margin-bottom: 20px;
        }
        .search-container {
            display: flex;
            flex-direction: column;
            margin-bottom: 24px;
        }
        .search-container form {
            display: flex;
            gap: 8px;
            align-items: center;
        }
        .search-container input[type="text"] {
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 4px 0 0 4px;
            width: 300px;
            font-family: Arial, sans-serif;
        }
        .search-button {
            padding: 10px 20px;
            background-color: #28a745;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            transition: background 0.2s;
        }
        .search-button:hover {
            background-color: #218838;
        }
        .reset-button {
            padding: 10px 20px;
            background-color: #808080;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            transition: background 0.2s;
        }
        .reset-button:hover {
            background-color: #565656;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
            font-weight: bold;
        }
        .actions-buttons {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 8px;
        }
        .actions-buttons button {
            padding: 8px 12px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            transition: opacity 0.2s;
        }
        .actions-buttons .update-button {
            background-color: #007bff;
            color: white;
        }
        .actions-buttons .suspend-button {
            background-color: #dc3545;
            color: white;
        }
        .actions-buttons button:hover {
            opacity: 0.8;
        }
        .no-results {
            text-align: center;
            font-style: italic;
            color: #777;
        }
        .pagination {
            margin-top: 20px;
            text-align: center;
        }
        .pagination a, .pagination span {
            padding: 8px 16px;
            margin: 0 4px;
            border: 1px solid #ddd;
            text-decoration: none;
            color: #007bff;
            border-radius: 4px;
            transition: background 0.2s;
        }
        .pagination a:hover {
            background-color: #f2f2f2;
        }
        .pagination .active {
            background-color: #007bff;
            color: white;
            border-color: #007bff;
        }
        .message.success {
            background-color: #28a745;
            color: white;
            padding: 10px;
            margin-bottom: 20px;
            border-radius: 5px;
            text-align: center;
        }
        @media (max-width: 700px) {
            .container {
                padding: 10px;
                max-width: 98vw;
            }
            .search-container input[type="text"] {
                width: 100px;
            }
            table, th, td {
                font-size: 12px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Search Form -->
        <div class="search-container">
            <h2>Search</h2>
            <form action="" method="GET">
                <input type="text" class="search-input" name="search" placeholder="Enter Profile ID or Name" value="<?php echo htmlspecialchars($searchQuery); ?>">
                <button type="submit" class="search-button">Search</button>
                <button type="button" class="reset-button" onclick="window.location.href = window.location.pathname;">Reset</button>
            </form>
        </div>
        <h2>User Profile List</h2>
        <table>
            <thead>
                <tr>
                    <th>User ID</th>
                    <th>Profile Name</th>
                    <th>Is Suspended</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($userProfiles)): ?>
                    <tr><td colspan="4" class="no-results">No results found.</td></tr>
                <?php else: ?>
                    <?php foreach ($userProfiles as $profile): ?>
                        <tr>
                            <td><?= htmlspecialchars($profile->getProfileId()) ?></td>
                            <td><?= htmlspecialchars($profile->getName()) ?></td>
                            <td><?= $profile->getIsSuspended() ? "Yes" : "No" ?></td>
                            <td class="actions-buttons">
                                <button onclick="window.location.href='updateUP.php?userid=<?= $profile->getProfileId() ?>';" class="update-button">Update</button>
                                <button onclick="return confirm('Are you sure you want to suspend this user?') ? window.location.href='suspendUP.php?userid=<?= $profile->getProfileId() ?>' : false;" class="suspend-button">Suspend</button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
        <div class="pagination">
        <?php
        $adjacents = 2;
        $start = max(1, $page - $adjacents);
        $end = min($totalPages, $page + $adjacents);

        if ($page > 1) {
            echo '<a href="?page=1' . ($searchQuery ? '&search=' . urlencode($searchQuery) : '') . '">First</a>';
            echo '<a href="?page=' . ($page - 1) . ($searchQuery ? '&search=' . urlencode($searchQuery) : '') . '">Previous</a>';
        }
        for ($i = $start; $i <= $end; $i++) {
            if ($i == $page) {
                echo '<span class="active">' . $i . '</span>';
            } else {
                echo '<a href="?page=' . $i . ($searchQuery ? '&search=' . urlencode($searchQuery) : '') . '">' . $i . '</a>';
            }
        }
        if ($page < $totalPages) {
            echo '<a href="?page=' . ($page + 1) . ($searchQuery ? '&search=' . urlencode($searchQuery) : '') . '">Next</a>';
            echo '<a href="?page=' . $totalPages . ($searchQuery ? '&search=' . urlencode($searchQuery) : '') . '">Last</a>';
        }
        ?>
        </div>
    </div>
</body>
</html>
