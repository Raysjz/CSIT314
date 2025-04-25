<?php
// Include necessary files
require_once(__DIR__ . '/../adminNavbar.php');
require_once(__DIR__ . '/../controllers/ViewUAController.php');

// Get the search query from GET request
$searchQuery = isset($_GET['search']) ? $_GET['search'] : null;

// Instantiate the controller and fetch user data
$controller = new ViewUserAccountController();
$userAccounts = $controller->viewUserAccounts($searchQuery);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View User Accounts</title>
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

    <div class="container">

        <!-- Search Form -->
        <div class="search-container">
            <h2>Search by Username or Id</h2>
            <form action="" method="GET">
                <input type="text" class="search-input" name="search" placeholder="Enter username or user ID">
                <button type="submit" class="search-button">Search</button>
            </form>
        </div>

        <h2>User List</h2>

        <table>
            <thead>
                <tr>
                    <th>User ID</th>
                    <th>Username</th>
                    <th>Password</th>
                    <th>Profile</th>
                    <th>Is Suspended</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                    // Check if there is a search query
                    $searchQuery = isset($_GET['search']) ? $_GET['search'] : null;

                    // Instantiate the UserAccount model and controller
                    $userAccount = new UserAccount(null, '', '', '', 0); // Empty fields since we just want to fetch users
                    $viewUAC = new ViewUserAccountController($userAccount);

                    // Fetch user accounts based on the search query
                    $userAccounts = $viewUAC->viewUserAccounts($searchQuery);

                    if (empty($userAccounts)) {
                        echo "<tr><td colspan='6' class='no-results'>No results found.</td></tr>";
                    } else {
                        foreach ($userAccounts as $account) {
                            echo "<tr>";
                            echo "<td>" . htmlspecialchars($account->getId()) . "</td>"; 
                            echo "<td>" . htmlspecialchars($account->getUsername()) . "</td>"; 
                            echo "<td>" . htmlspecialchars($account->getPassword()) . "</td>";
                            echo "<td>" . htmlspecialchars($account->getProfile()) . "</td>";
                            echo "<td>" . htmlspecialchars($account->getIsSuspended() ? 'Yes' : 'No') . "</td>";
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
    </div>
</body>
</html>

