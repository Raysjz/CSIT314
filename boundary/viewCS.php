<?php
session_start();  // Start the session to access session variables
if ($_SESSION['profileName'] !== 'Cleaner') {
    header('Location: ../login.php');
    exit();
}
require_once(__DIR__ . '/../cleanerNavbar.php');
require_once(__DIR__ . '/../controllers/ViewCSController.php');

// Get the search query from GET request
$searchQuery = isset($_GET['search']) ? $_GET['search'] : null;

// Instantiate the controller and fetch cleaning service data
$accountId = $_SESSION['user_id'] ?? null; // Only set if user is logged in as cleaner

$controller = new ViewCleaningServicesController();
$cleaningServices = $controller->viewCleaningServices($accountId);
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
        .actions-buttons button { padding: 8px 12px; margin-right: 5px; border: none; border-radius: 4px; cursor: pointer; }
        .actions-buttons .update-button { background-color: #28a745; color: white; text-decoration: none; }
        .actions-buttons .suspend-button { background-color: #dc3545; color: white; }
        .actions-buttons button:hover { opacity: 0.8; }
        .no-results { text-align: center; font-style: italic; color: #777; }
        .desc-cell { max-width: 300px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
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
                    <th>Title</th>
                    <th>Description</th>
                    <th>Price</th>
                    <th>Availability</th>
                    <th>Is Suspended</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                    if (empty($cleaningServices)) {
                        echo "<tr><td colspan='9' class='no-results'>No results found.</td></tr>";
                    } else {
                        foreach ($cleaningServices as $service) {
                            echo "<tr>";
                            echo "<td>" . htmlspecialchars($service->getServiceId()) . "</td>";
                            echo "<td>" . htmlspecialchars($service->getTitle()) . "</td>";
                            echo "<td class='desc-cell'>" . htmlspecialchars($service->getDescription()) . "</td>";
                            echo "<td>$" . htmlspecialchars(number_format($service->getPrice(), 2)) . "</td>";
                            echo "<td>" . htmlspecialchars($service->getAvailability()) . "</td>";
                            echo "<td>" . ($service->isSuspended() ? 'Yes' : 'No') . "</td>";
                            echo "<td class='actions-buttons'>
                                    <button onclick=\"window.location.href='updateCS.php?serviceid=" . $service->getServiceId() . "';\" class='update-button'>Edit</button>
                                    <button onclick=\"return confirm('Are you sure you want to suspend this service?') ? window.location.href='suspendCS.php?serviceid=" . $service->getServiceId() . "' : false;\" class='suspend-button'>Suspend</button>
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

