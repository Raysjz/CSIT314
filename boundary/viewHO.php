<?php
session_start();  // Start the session to access session variables
if ($_SESSION['profileName'] !== 'Homeowner') {
    header('Location: ../login.php');
    exit();
}
require_once(__DIR__ . '/../homeownerNavbar.php');
require_once(__DIR__ . '/../controllers/ViewHOController.php');
require_once(__DIR__ . '/../controllers/SearchHOController.php');
require_once(__DIR__ . '/../controllers/PlatformCategoryController.php');
require_once(__DIR__ . '/../controllers/ShortlistController.php');

// Handle search query
$searchQuery = isset($_GET['search']) ? $_GET['search'] : '';
$displayedServices = [];

if ($searchQuery !== '') {
    $controller = new SearchHOCleaningServicesController();
    $displayedServices = $controller->searchHOCleaningServices($searchQuery);
} else {
    $viewController = new ViewHOCleaningServicesController();
    $displayedServices = $viewController->viewHOCleaningServices();
}

$homeownerAccountId = $_SESSION['user_id'];
$shortlistController = new ShortlistController();
$shortlistedServices = $shortlistController->getShortlistedServices($homeownerAccountId);

// Build an array of shortlisted service IDs for easy lookup
$shortlistedIds = array_map(function($svc) {
    return is_object($svc) ? $svc->service_id : $svc['service_id'];
}, $shortlistedServices);


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Cleaning Services</title>
    <style>
        body { font-family: Arial; background: #f4f4f4; margin: 0; padding: 40px; }
        .container { background: white; padding: 30px; max-width: 1200px; margin: auto; margin-top: 80px; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); }
        h1, h2 { margin-bottom: 20px; }
        .search-container { margin-bottom: 20px; text-align: center; }
        .search-input { padding: 10px; border: 1px solid #ddd; border-radius: 4px; width: 60%; margin-bottom: 10px; }
        .search-button { padding: 10px 20px; background-color: #007bff; color: white; border: none; border-radius: 4px; cursor: pointer; }
        .search-button:hover { background-color: #0056b3; }
        .reset-button {padding: 10px 20px;background-color: #808080; color: white; border: none;border-radius: 4px;cursor: pointer;}
        .reset-button:hover {background-color: #565656;}
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 10px; text-align: left; }
        th { background-color: #f2f2f2; font-weight: bold; }
        .no-results { text-align: center; font-style: italic; color: #777; }
        .desc-cell { max-width: 300px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
        .action-links a { margin-right: 8px; text-decoration: none; color: #007bff; }
        .action-links a:hover { text-decoration: underline; }
    </style>
</head>
<body>
    <div class="container">

        <!-- Search Form -->
        <div class="search-container">
            <h2>Search by Title or Service ID</h2>
            <form action="" method="GET">
                <input type="text" class="search-input" name="search" placeholder="Enter service title or ID" value="<?php echo htmlspecialchars($searchQuery); ?>">
                <button type="submit" class="search-button">Search</button>
                <button type="reset" class="reset-button" onclick="window.location.href = window.location.pathname;">Reset</button>
            </form>
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
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if (empty($displayedServices)) {
                    echo "<tr><td colspan='6' class='no-results'>No results found.</td></tr>";
                } else {
                    foreach ($displayedServices as $service) {
                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($service->getServiceId()) . "</td>";
                        echo "<td>" . htmlspecialchars($service->getCategoryName()) . "</td>";
                        echo "<td>" . htmlspecialchars($service->getTitle()) . "</td>";
                        echo "<td class='desc-cell'>" . htmlspecialchars($service->getDescription()) . "</td>";
                        echo "<td>$" . htmlspecialchars(number_format($service->getPrice(), 2)) . "</td>";
                        echo "<td>" . htmlspecialchars($service->getAvailability()) . "</td>";
                        echo "<td class='action-links'>
                              <a href='viewHOServiceDetails.php?id=" . $service->getServiceId() . "'>View Details</a>";
                            if (in_array($service->getServiceId(), $shortlistedIds)) {
                                echo " | <a href='removeShortlist.php?id=" . $service->getServiceId() . "' style='color:#dc3545;'>Remove from Shortlist</a>";
                            } else {
                                echo " | <a href='addShortlist.php?id=" . $service->getServiceId() . "' style='color:#007bff;'>Add to Shortlist</a>";
                            }
                            echo "</td>";

                        echo "</tr>";
                    }
                }
                ?>
            </tbody>
        </table>
    </div>
</body>
</html>
