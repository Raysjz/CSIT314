<?php
session_start();  // Start the session to access session variables

// 1. Access Control: Only allow User Admin
if ($_SESSION['profileName'] !== 'User Admin') {
    header('Location: ../login.php');
    exit();
}

// 2. Include necessary files (navbar and controller)
require_once(__DIR__ . '/adminNavbar.php');
require_once(__DIR__ . '/../controllers/ViewUAController.php');

// 3. Pagination setup
$perPage = 10;
$page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
$offset = ($page - 1) * $perPage;

// 4. Get search query from GET request (if any)
$searchQuery = isset($_GET['search']) ? $_GET['search'] : null;

// 5. Fetch user accounts using the controller
$controller = new ViewUserAccountController();
$result = $controller->viewUserAccounts($searchQuery, $perPage, $offset);
$userAccounts = $result['data'];
$totalPages = ceil($result['total'] / $perPage);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View User Accounts</title>
    <style>
        body { font-family: Arial; background: #f4f4f4; margin: 0; padding: 40px; }
        .container { background: white; padding: 30px; width: 100%; margin-top: 80px; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); box-sizing: border-box; }
        h1 { margin-bottom: 20px; }
        .search-container { margin-bottom: 20px; text-align: center; }
        .search-input { padding: 10px; border: 1px solid #ddd; border-radius: 4px; width: 60%; margin-bottom: 10px; }
        .search-button { padding: 10px 20px; background-color: #28a745; color: white; border: none; border-radius: 4px; cursor: pointer; }
        .search-button:hover { background-color: #0056b3; }
        .reset-button { padding: 10px 20px; background-color: #808080; color: white; border: none; border-radius: 4px; cursor: pointer; }
        .reset-button:hover { background-color: #565656; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 10px; text-align: left; }
        th { background-color: #f2f2f2; font-weight: bold; }
        .actions-buttons button { padding: 8px 12px; margin-right: 5px; border: none; border-radius: 4px; cursor: pointer; }
        .actions-buttons .update-button { background-color: #007bff; color: white; }
        .actions-buttons .suspend-button { background-color: #dc3545; color: white; }
        .actions-buttons button:hover { opacity: 0.8; }
        .no-results { text-align: center; font-style: italic; color: #777; }
        .pagination { margin-top: 20px; text-align: center; }
        .pagination a, .pagination span { 
            padding: 8px 16px; 
            margin: 0 4px; 
            border: 1px solid #ddd; 
            text-decoration: none;
            color: #007bff;
            border-radius: 4px;
        }
        .pagination a:hover { background-color: #f2f2f2; }
        .pagination .active { 
            background-color: #007bff; 
            color: white; 
            border-color: #007bff;
        }
    </style>
</head>
<body>

<div class="container">

    <!-- Search Form -->
    <div class="search-container">
        <h2>Search by Username or ID</h2>
        <form action="" method="GET">
            <!-- Always reset to page 1 on new search -->
            <input type="hidden" name="page" value="1">
            <input type="text" class="search-input" name="search" 
                   placeholder="Enter username or user ID" 
                   value="<?= htmlspecialchars($searchQuery) ?>">
            <button type="submit" class="search-button">Search</button>
            <button type="reset" class="reset-button" 
                    onclick="window.location.href=window.location.pathname">Reset</button>
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
            <?php
            // Show "No results" if the user list is empty
            if (empty($userAccounts)) {
                echo "<tr><td colspan='8' class='no-results'>No results found.</td></tr>";
            } else {
                // Loop through user accounts and display each in a table row
                foreach ($userAccounts as $account) {
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($account->getId()) . "</td>"; 
                    echo "<td>" . htmlspecialchars($account->getUsername()) . "</td>"; 
                    echo "<td>" . htmlspecialchars($account->getPassword()) . "</td>";
                    echo "<td>" . htmlspecialchars($account->getEmail()) . "</td>";
                    echo "<td>" . htmlspecialchars($account->getFullName()) . "</td>";
                    echo "<td>" . htmlspecialchars($account->getProfile()) . "</td>";
                    echo "<td>" . ($account->getIsSuspended() ? 'Yes' : 'No') . "</td>";
                    echo "<td class='actions-buttons'>
                            <button onclick=\"window.location.href='updateUA.php?userid=" . $account->getId() . "';\" class='update-button'>Update</button>
                            <button onclick=\"return confirm('Are you sure you want to suspend this user?') ? window.location.href='suspendUA.php?userid=" . $account->getId() . "' : false;\" class='suspend-button'>Suspend</button>
                          </td>";
                    echo "</tr>";
                }
            }
            ?>
        </tbody>
    </table>

    <!-- Pagination Controls -->
    <div class="pagination">
        <?php if($page > 1): ?>
            <a href="?page=1<?= $searchQuery ? '&search='.urlencode($searchQuery) : '' ?>">First</a>
            <a href="?page=<?= $page-1 ?><?= $searchQuery ? '&search='.urlencode($searchQuery) : '' ?>">Previous</a>
        <?php endif; ?>

        <?php for($i = max(1, $page-2); $i <= min($page+2, $totalPages); $i++): ?>
            <a href="?page=<?= $i ?><?= $searchQuery ? '&search='.urlencode($searchQuery) : '' ?>"
               <?= $i === $page ? 'class="active"' : '' ?>>
                <?= $i ?>
            </a>
        <?php endfor; ?>

        <?php if($page < $totalPages): ?>
            <a href="?page=<?= $page+1 ?><?= $searchQuery ? '&search='.urlencode($searchQuery) : '' ?>">Next</a>
            <a href="?page=<?= $totalPages ?><?= $searchQuery ? '&search='.urlencode($searchQuery) : '' ?>">Last</a>
        <?php endif; ?>
    </div>
</div>
</body>
</html>
