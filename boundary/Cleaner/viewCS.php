<?php
session_start();
if ($_SESSION['profileName'] !== 'Cleaner') {
    header('Location: ../login.php');
    exit();
}

// Include dependencies
require_once __DIR__ . '/cleanerNavbar.php';
require_once __DIR__ . '/../../controllers/Cleaner/ViewCSController.php';
require_once __DIR__ . '/../../controllers/Cleaner/SearchCSController.php';
require_once __DIR__ . '/../../controllers/PlatformMgmt/ServiceCategoryController.php';
require_once __DIR__ . '/../../controllers/ShortlistController.php';
require_once __DIR__ . '/../../controllers/ServiceViewController.php';

$searchQuery = isset($_GET['search']) ? $_GET['search'] : null;
$accountId = $_SESSION['user_id'] ?? null;

if ($searchQuery) {
    $controller = new SearchCleaningServicesController();
    $cleaningServices = $controller->searchCleaningServices($searchQuery, $accountId);
} else {
    $controller = new ViewCleaningServicesController();
    $cleaningServices = $controller->viewCleaningServices($accountId);
}

// Instantiate controllers ONCE, use inside loop
$shortlistController = new ShortlistController();
$viewController = new ServiceViewController();



?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Cleaning Services</title>
    <style>
        body { font-family: Arial; background: #f4f4f4; margin: 0; padding: 40px; }
        .container { background: white; padding: 30px; width: 100%; margin-top: 80px; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); box-sizing: border-box; }
        h1, h2 { margin-bottom: 20px; }
        .search-container {display: flex; flex-direction:column; margin-right: 20px;}
        .search-container input[type="text"] {padding: 8px;border: 1px solid #ccc;border-radius: 4px 0 0 4px;width: 300px; /* Adjust width as needed */font-family: Arial, sans-serif;}
        .search-button { padding: 10px 20px; background-color: #007bff; color: white; border: none; border-radius: 4px; cursor: pointer; }
        .search-button:hover { background-color: #0056b3; }
        .reset-button {padding: 10px 20px;background-color: #808080; color: white; border: none;border-radius: 4px;cursor: pointer;}
        .reset-button:hover {background-color: #565656;}
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 10px; text-align: left; }
        th { background-color: #f2f2f2; font-weight: bold; }
        .actions-buttons button { padding: 8px 12px; margin-right: 5px; border: none; border-radius: 4px; cursor: pointer; }
        .actions-buttons .update-button { background-color: #28a745; color: white; text-decoration: none; }
        .actions-buttons .suspend-button { background-color: #dc3545; color: white; }
        .actions-buttons button:hover { opacity: 0.8; }
        .no-results { text-align: center; font-style: italic; color: #777; }
        .create-button {
            margin-top: 15px; /* spacing below the form */
            padding: 10px 20px;
            background-color:rgb(122, 130, 139); /* same as search-button */
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-family: Arial, sans-serif;
            font-size: 1rem;
            transition: background-color 0.3s ease;
        }

        .create-button:hover {
            background-color:rgb(85, 90, 95); /* same hover as search-button */
        }
        .desc-cell { max-width: 300px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
    </style>
</head>
<body>

    <div class="container">

        <!-- Search Form -->
        <div class="search-container">
            <h2>Search by Service ID or Title</h2>
            <form action="" method="GET">
                <input type="text" class="search-input" name="search" placeholder="Enter Service ID or Title" value="<?php echo htmlspecialchars($searchQuery); ?>">
                <button type="submit" class="search-button">Search</button>
                <button type="reset" class="reset-button" onclick="window.location.href = window.location.pathname;">Reset</button>
            </form>
        </div>
        <!-- Create New Service Button (right above the table) -->
        <div style="text-align: left; margin-bottom: 10px;">
            <button class="create-button" onclick="window.location.href='createCS.php'">Create New Service</button>
        </div>

        <h2>Cleaning Services List</h2>

        <table>
            <thead>
                <tr>
                    <th>Service ID</th>
                    <th>Category</th>
                    <th>Title</th>
                    <th>Description</th>
                    <th>Price</th>
                    <th>Availability</th>
                    <th>Is Suspended</th>
                    <th>Views Count</th>
                    <th>Shortlist Count</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                    if (empty($cleaningServices)) {
                        echo "<tr><td colspan='10' class='no-results'>No results found.</td></tr>";
                    } else {
                        foreach ($cleaningServices as $service) {
                            //---- Shortlist and Views-----//
                            $serviceId = $service->getServiceId();
                            $viewCount = $viewController->getViewCount($serviceId);
                            $shortlistCount = $shortlistController->getShortlistCount($serviceId);

                            // Numeric error safeguard
                            $price = $service->getPrice();
                            $priceValue = is_numeric($price) ? (float)$price : 0.00;

                            $categoryController = new ServiceCategoryController();
                            $category = $categoryController->getCategoryById($service->getCategoryId());

                            echo "<tr>";
                            echo "<td>" . htmlspecialchars($serviceId) . "</td>";
                            echo "<td>" . htmlspecialchars($category->getName()) . "</td>";
                            echo "<td>" . htmlspecialchars($service->getTitle()) . "</td>";
                            echo "<td class='desc-cell'>" . htmlspecialchars($service->getDescription()) . "</td>";
                            echo "<td>$" . htmlspecialchars($price) . "</td>";
                            echo "<td>" . htmlspecialchars($service->getAvailability()) . "</td>";
                            echo "<td>" . ($service->isSuspended() ? 'Yes' : 'No') . "</td>";
                            echo "<td>$viewCount</td>";
                            echo "<td>$shortlistCount</td>";
                            echo "<td class='actions-buttons'>
                                    <button onclick=\"window.location.href='updateCS.php?serviceid=" . $serviceId . "';\" class='update-button'>Edit</button>
                                    <button onclick=\"return confirm('Are you sure you want to suspend this service?') ? window.location.href='suspendCS.php?serviceid=" . $serviceId . "' : false;\" class='suspend-button'>Suspend</button>
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
