<?php
// View User Accounts

session_start(); // Start session

// Redirect if not User Admin
if ($_SESSION["profileName"] !== "User Admin") {
    header("Location: ../login.php");
    exit();
}

// Include dependencies
require_once __DIR__ . "/adminNavbar.php";
require_once __DIR__ . '/../../controllers/UserAdmin/viewUAController.php';
require_once __DIR__ . '/../../controllers/UserAdmin/SearchUAController.php';

// Pagination and search parameters
$perPage = 10;
$page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
$offset = ($page - 1) * $perPage;
$searchQuery = isset($_GET['search']) ? trim($_GET['search']) : null;

// Fetch user accounts (with or without search)
if ($searchQuery) {
    $searchController = new SearchUserAccountController();
    $result = $searchController->searchUserAccounts($searchQuery, $perPage, $offset);
} else {
    $viewController = new ViewUserAccountController();
    $result = $viewController->viewUserAccounts($perPage, $offset);
}
$userAccounts = $result['data'];
$total = $result['total'];
$totalPages = ceil($total / $perPage);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>View User Accounts</title>
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
            max-width: 1100px;
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
        .search-button, .reset-button {
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            color: white;
            cursor: pointer;
            transition: background 0.2s;
        }
        .search-button {
            background-color: #28a745;
        }
        .search-button:hover {
            background-color: #218838;
        }
        .reset-button {
            background-color: #808080;
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
            white-space: nowrap;
        }
        .actions-buttons button {
            padding: 8px 12px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            transition: opacity 0.2s;
        }
        .update-button {
            background-color: #007bff;
            color: white;
        }
        .suspend-button {
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
            <input type="hidden" name="page" value="1" />
            <input type="text" class="search-input" name="search" 
                   placeholder="Enter User ID, Email or Name" 
                   value="<?= htmlspecialchars($searchQuery) ?>" />
            <button type="submit" class="search-button">Search</button>
            <button type="button" class="reset-button" 
                    onclick="window.location.href='<?= strtok($_SERVER['REQUEST_URI'], '?') ?>'">Reset</button>
        </form>
    </div>

    <h2>User List</h2>

    <!-- User Accounts Table -->
    <table>
        <thead>
            <tr>
                <th>User ID</th>
                <th>Username</th>
                <th>Password</th>
                <th>Email</th>
                <th>Full Name</th>
                <th>Profile</th>
                <th>Is Suspended</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($userAccounts)): ?>
                <tr>
                    <td colspan="8" class="no-results">No results found.</td>
                </tr>
            <?php else: foreach ($userAccounts as $account): ?>
                <tr>
                    <td><?= htmlspecialchars($account->getId()) ?></td>
                    <td><?= htmlspecialchars($account->getUsername()) ?></td>
                    <td><?= htmlspecialchars($account->getPassword()) ?></td>
                    <td><?= htmlspecialchars($account->getEmail()) ?></td>
                    <td><?= htmlspecialchars($account->getFullName()) ?></td>
                    <td><?= htmlspecialchars($account->getProfile()) ?></td>
                    <td><?= $account->getIsSuspended() ? 'Yes' : 'No' ?></td>
                    <td class="actions-buttons">
                        <button onclick="window.location.href='updateUA.php?userid=<?= $account->getId() ?>';" class="update-button">Update</button>
                        <button onclick="return confirm('Are you sure you want to suspend this user?') ? window.location.href='suspendUA.php?userid=<?= $account->getId() ?>' : false;" class="suspend-button">Suspend</button>
                    </td>
                </tr>
            <?php endforeach; endif; ?>
        </tbody>
    </table>

    <!-- Pagination Controls -->
    <div class="pagination">
        <?php
        $adjacents = 2;
        $start = max(1, $page - $adjacents);
        $end = min($totalPages, $page + $adjacents);

        // First/Prev
        if ($page > 1) {
            echo '<a href="?page=1'.($searchQuery ? '&search='.urlencode($searchQuery) : '').'">First</a>';
            echo '<a href="?page='.($page-1).($searchQuery ? '&search='.urlencode($searchQuery) : '').'">Previous</a>';
        }

        // Numbered pages
        for ($i = $start; $i <= $end; $i++) {
            if ($i == $page) {
                echo '<span class="active">'.$i.'</span>';
            } else {
                echo '<a href="?page='.$i.($searchQuery ? '&search='.urlencode($searchQuery) : '').'">'.$i.'</a>';
            }
        }

        // Next/Last
        if ($page < $totalPages) {
            echo '<a href="?page='.($page+1).($searchQuery ? '&search='.urlencode($searchQuery) : '').'">Next</a>';
            echo '<a href="?page='.$totalPages.($searchQuery ? '&search='.urlencode($searchQuery) : '').'">Last</a>';
        }
        ?>
    </div>
</div>
</body>
</html>
