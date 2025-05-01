<?php
session_start();  // Start the session to access session variables
if (!isset($_SESSION['profileName']) || $_SESSION['profileName'] !== 'Homeowner') {
    header('Location: ../login.php');
    exit();
}
require_once(__DIR__ . '/../homeownerNavbar.php');
require_once(__DIR__ . '/../controllers/ViewHOShortlistController.php');

$homeownerAccountId = $_SESSION['user_id'];
$controller = new ViewHOShortlistController();
$shortlistedServices = $controller->getShortlistedServices($homeownerAccountId);

// Shortlist search
$searchQuery = isset($_GET['search']) ? trim($_GET['search']) : '';
$filteredServices = $shortlistedServices;

if ($searchQuery !== '') {
    $filteredServices = array_filter($shortlistedServices, function($service) use ($searchQuery) {
        return stripos((string)$service->shortlist_id, $searchQuery) !== false
            || stripos((string)$service->service_id, $searchQuery) !== false
            || stripos($service->title, $searchQuery) !== false;
    });
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Shortlist</title>
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
        .back-button { background: #6c757d; color: white; padding: 8px 16px; border-radius: 4px; text-decoration: none; margin-bottom: 20px; display: inline-block;}
        .back-button:hover { background: #5a6268; }
    </style>
</head>
<body>
    <div class="container">
        <a href="viewHO.php" class="back-button">Back to List</a>
        <!-- Search Form -->
        <div class="search-container">
            <h2>Search by Shortlist ID, Service ID, or Title</h2>
            <form action="" method="GET">
                <input type="text" class="search-input" name="search" placeholder="Enter shortlist ID, service ID, or title" value="<?php echo htmlspecialchars($searchQuery); ?>">
                <button type="submit" class="search-button">Search</button>
                <button type="reset" class="reset-button" onclick="window.location.href = window.location.pathname;">Reset</button>
            </form>
        </div>

        <h2>Shortlist</h2>
        <table>
            <thead>
                <tr>
                    <th>Shortlist ID</th>
                    <th>Service ID</th>
                    <th>Title</th>
                    <th>Description</th>
                    <th>Price</th>
                    <th>Availability</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
            <?php
            if (empty($filteredServices)) {
                echo "<tr><td colspan='7' class='no-results'>No results found.</td></tr>";
            } else {
                foreach ($filteredServices as $service) {
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($service->shortlist_id) . "</td>";
                    echo "<td>" . htmlspecialchars($service->service_id) . "</td>";
                    echo "<td>" . htmlspecialchars($service->title) . "</td>";
                    echo "<td class='desc-cell'>" . htmlspecialchars($service->description) . "</td>";
                    echo "<td>$" . htmlspecialchars(number_format($service->price, 2)) . "</td>";
                    echo "<td>" . htmlspecialchars($service->availability) . "</td>";
                    echo "<td class='action-links'>
                            <a href='shortlistBooking.php?id=" . $service->service_id . "'>View Details</a>
                            | <a href='removeShortlist.php?id=" . $service->service_id . "' style='color:#dc3545;'>Remove</a>
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


