<?php
session_start();
require_once(__DIR__ . '/adminNavbar.php');
require_once(__DIR__ . '/../../controllers/UserAdmin/viewUAController.php');
require_once(__DIR__ . '/../../controllers/UserAdmin/SearchUAController.php');

// Pagination parameters
$perPage = 10;
$page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
$offset = ($page - 1) * $perPage;

// Search query (if any)
$searchQuery = isset($_GET['search']) ? trim($_GET['search']) : null;

// Fetch accounts and total count using controllers (BCE)
if ($searchQuery) {
    $searchController = new SearchUserAccountController();
    $result = $searchController->searchUserAccounts($searchQuery, $perPage, $offset);
    $userAccounts = $result['data'];
    $total = $result['total'];
} else {
    $viewController = new ViewUserAccountController();
    $result = $viewController->viewUserAccounts(null, $perPage, $offset);
    $userAccounts = $result['data'];
    $total = $result['total'];
}
$totalPages = ceil($total / $perPage);
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
        .search-container {display: flex; flex-direction:column; margin-right: 20px;}
        .search-container input[type="text"] {padding: 8px;border: 1px solid #ccc;border-radius: 4px 0 0 4px;width: 300px; /* Adjust width as needed */font-family: Arial, sans-serif;}
        .search-button { padding: 10px 20px; background-color: #28a745; color: white; border: none; border-radius: 4px; cursor: pointer; }
        .search-button:hover { background-color: #218838; }
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
        .actions-buttons {
    display: flex;
    justify-content: center; /* Center horizontally */
    align-items: center;     /* Center vertically (optional) */
    gap: 8px;                /* Space between buttons */
    }
        .pagination a:hover { background-color: #f2f2f2; }
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
    </style>
</head>
<body>

<div class="container">
      <?php  if (isset($_SESSION['success_message'])) {
            echo '<div class="message success">' . htmlspecialchars($_SESSION['success_message']) . '</div>';
            unset($_SESSION['success_message']);
        }?>
    <!-- Search Form -->
    <div class="search-container">
        <h2>Search</h2>
        <form action="" method="GET">
            <!-- Always reset to page 1 on new search -->
            <input type="hidden" name="page" value="1">
            <input type="text" class="search-input" name="search" 
                   placeholder="Enter any field" 
                   value="<?= htmlspecialchars($searchQuery) ?>">
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
                <tr><td colspan="8" class="no-results">No results found.</td></tr>
            <?php else: foreach ($userAccounts as $account): ?>
                <tr>
                    <td><?= htmlspecialchars($account->getId()) ?></td>
                    <td><?= htmlspecialchars($account->getUsername()) ?></td>
                    <td><?= htmlspecialchars($account->getPassword()) ?></td>
                    <td><?= htmlspecialchars($account->getEmail()) ?></td>
                    <td><?= htmlspecialchars($account->getFullName()) ?></td>
                    <td><?= htmlspecialchars($account->getProfile()) ?></td>
                    <td><?= $account->getIsSuspended() ? 'Yes' : 'No' ?></td>
                    <td class='actions-buttons'>
                        <button onclick="window.location.href='updateUA.php?userid=<?= $account->getId() ?>';" class='update-button'>Update</button>
                        <button onclick="return confirm('Are you sure you want to suspend this user?') ? window.location.href='suspendUA.php?userid=<?= $account->getId() ?>' : false;" class='suspend-button'>Suspend</button>
                    </td>
                </tr>
            <?php endforeach; endif; ?>
        </tbody>
    </table>

    <!-- Pagination Controls -->
    <div class="pagination">
        <?php
        $adjacents = 2; // How many numbers to show on each side
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
