<?php
// viewPC.php -> viewPlatformCategoryController.php -> PlatformCategory.php
// viewPC.php -> searchPlatformCategoryController.php -> PlatformCategory.php
session_start();  // Start the session to access session variables
if ($_SESSION['profileName'] !== 'Platform Management') {
    header('Location: ../login.php');
    exit();
}
// Include necessary files
require_once(__DIR__ . '/platformNavbar.php');
require_once(__DIR__ . '/../../controllers/PlatformMgmt/searchPlatformCategoryController.php');
require_once(__DIR__ . '/../../controllers/PlatformMgmt/viewPlatformCategoryController.php');


$searchQuery = isset($_GET['search']) ? trim($_GET['search']) : null;

if ($searchQuery) {
    $searchController = new SearchPlatformCategoryController();
    $serviceCategories = $searchController->searchPlatformCategory($searchQuery);
} else {
    $viewController = new ViewPlatformCategoryController();
    $serviceCategories = $viewController->viewPlatformCategory();
}

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
        .detail { margin-bottom: 15px; }
        .label { font-weight: bold; }
        .search-container h2 { margin-top: 0; }
        .search-container {display: flex; flex-direction:column; margin-right: 20px;}
        .search-container input[type="text"] {padding: 8px;border: 1px solid #ccc;border-radius: 4px 0 0 4px;width: 300px; /* Adjust width as needed */font-family: Arial, sans-serif;}
        .search-button { padding: 10px 20px; background-color: #007bff; color: white; border: none; border-radius: 4px; cursor: pointer; }
        .search-button:hover { background-color: #0056b3; }
        .reset-button {padding: 10px 20px;background-color: #808080; color: white; border: none;border-radius: 4px;cursor: pointer;}
        .reset-button:hover {background-color: #565656; /* Darker Gray */}
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 10px; text-align: left; }
        th { background-color: #f2f2f2; font-weight: bold; }
        .actions-buttons {
            display: flex;
            justify-content: center; /* centers buttons horizontally */
            gap: 8px; /* space between buttons */
            align-items: center; /* vertically center if needed */
        }
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
        <h2>Search by Category Name or ID</h2>
            <form action="" method="GET">
                <input type="text" class="search-input" name="search" placeholder="Enter category or category ID" value="<?php echo htmlspecialchars($searchQuery); ?>">
                <button type="submit" class="search-button">Search</button>
                <button type="reset" class="reset-button" onclick="window.location.href = window.location.pathname;">Reset</button>
            </form>
        </div>

        

        <h2>Service Categories List</h2>

        <table>
            <thead>
                <tr>
                    <th>Category ID</th>
                    <th>Category Name</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                    if (empty($serviceCategories)) {
                        echo "<tr><td colspan='6' class='no-results'>No results found.</td></tr>";
                    } else {
                        foreach ($serviceCategories as $services) {
                            echo "<tr>";
                            echo "<td>" . htmlspecialchars($services->getId()) . "</td>"; 
                            echo "<td>" . htmlspecialchars($services->getName()) . "</td>"; 
                            echo "<td>" . htmlspecialchars($services->getIsSuspended() ? 'Yes' : 'No') . "</td>";
                            echo "<td class='actions-buttons'>
                                    <button onclick=\"window.location.href='updatePC.php?userid=" . $services->getId() . "';\" class='update-button'>Update</button>
                                    <button onclick=\"return confirm('Are you sure you want to suspend this user?') ? window.location.href='suspendPC.php?userid=" . $services->getId() . "' : false;\" class='suspend-button'>Suspend</button>
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

