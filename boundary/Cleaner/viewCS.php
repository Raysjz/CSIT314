<?php
// Cleaner View Cleaning Services

session_start(); // Start session

// Redirect if not Cleaner
if ($_SESSION['profileName'] !== 'Cleaner') {
    header('Location: ../login.php');
    exit();
}

// Include dependencies
require_once __DIR__ . '/cleanerNavbar.php';
require_once __DIR__ . '/../../controllers/Cleaner/ViewCSController.php';
require_once __DIR__ . '/../../controllers/Cleaner/SearchCSController.php';
require_once __DIR__ . '/../../controllers/Cleaner/CleaningServicesMiscController.php';
require_once __DIR__ . '/../../controllers/PlatformMgmt/ServiceCategoryController.php';
require_once __DIR__ . '/../../controllers/ShortlistController.php';
require_once __DIR__ . '/../../controllers/ServiceViewController.php';

// Get search query and account ID
$accountId = $_SESSION['user_id'] ?? null;

$perPage = 10;
$page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
$offset = ($page - 1) * $perPage;
$searchQuery = isset($_GET['search']) ? trim($_GET['search']) : null;


$shortlistController = new ShortlistController();
$sviewController = new ServiceViewController();
$categoryController = new ServiceCategoryController();
$miscController = new CleaningServicesMiscController();

if ($searchQuery) {
    $searchController = new SearchCleaningServicesController();
    $cleaningServices = $searchController->searchCleaningServices($searchQuery, $perPage, $offset, $accountId);
    $total = $miscController->countSearchCleaningServices($searchQuery, $accountId);
} else {
    $viewController = new ViewCleaningServicesController();
    $cleaningServices = $viewController->viewCleaningServices($perPage, $offset, $accountId);
    $total = $miscController->countAllCleaningServices($accountId);
}
$totalPages = ceil($total / $perPage);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Cleaning Services</title>
        <style>
            body{
                font-family: Arial,sans-serif;
                background: #f4f4f4;
                margin: 0;
                padding: 10px
            }
            .container{
                background: #fff;
                padding: 24px;
                width: 100%;
                max-width: 1600px;
                margin: 20px auto 0 auto;
                border-radius: 8px;
                box-shadow: 0 2px 8px rgba(0,0,0,0.1);
                box-sizing: border-box;
                overflow-x: auto
            }
            h1,h2{
                margin-bottom: 20px
            }
            .search-container{
                display: flex;
                flex-direction: column;
                margin-bottom: 24px
            }
            .search-container form{
                display: flex;
                gap: 8px;
                align-items: center
            }
            .search-container input[type="text"]{
                padding: 8px;
                border: 1px solid #ccc;
                border-radius: 4px 0 0 4px;
                width: 300px;
                font-family: Arial,sans-serif
            }
            .search-button{
                padding: 10px 20px;
                background-color: #007bff;
                color: white;
                border: none;
                border-radius: 4px;
                cursor: pointer;
                transition: background 0.2s
            }
            .search-button:hover{
                background-color: #0056b3
            }
            .reset-button{
                padding: 10px 20px;
                background-color: #808080;
                color: white;
                border: none;
                border-radius: 4px;
                cursor: pointer;
                transition: background 0.2s
            }
            .reset-button:hover{
                background-color: #565656
            }
            .create-button{
                margin-top: 0;
                margin-bottom: 15px;
                padding: 10px 20px;
                background-color: rgb(122,130,139);
                color: white;
                border: none;
                border-radius: 4px;
                cursor: pointer;
                font-family: Arial,sans-serif;
                font-size: 1rem;
                transition: background-color 0.3s ease
            }
            .create-button:hover{
                background-color: rgb(85,90,95)
            }
            table{
                width: 100%;
                border-collapse: collapse;
                margin-top: 20px;
                min-width: 1400px
            }
            th,td{
                border: 1px solid #ddd;
                padding: 10px;
                text-align:left
            }
            th.actions-col,td.actions-col{
                min-width: 180px
            }
            .desc-cell{
                max-width: 300px;
                overflow: hidden;
                text-overflow: ellipsis;
                white-space: nowrap
            }
            .actions-buttons{
                display: flex;
                gap: 8px;
                align-items: center;
                justify-content: center
            }
            .actions-buttons button{
                padding: 8px 12px;
                border: none;
                border-radius: 4px;
                cursor: pointer;
                transition: opacity 0.2s
            }
            .actions-buttons .update-button{
                background-color: #28a745;
                color: white
            }
            .actions-buttons .suspend-button{
                background-color: #dc3545;
                color: white
            }
            .actions-buttons button:hover{
                opacity: 0.8
            }
            .no-results{
                text-align: center;
                font-style: italic;
                color: #777
            }
            .pagination{
                margin-top: 20px;
                text-align: center
            }
            .pagination a,.pagination span{
                padding: 8px 16px;
                margin: 0 4px;
                border: 1px solid #ddd;
                text-decoration: none;
                color: #007bff;
                border-radius: 4px;
                transition: background 0.2s
            }
            .pagination a:hover{
                background-color: #f2f2f2
            }
            .pagination .active{
                background-color: #007bff;
                color: white;
                border-color: #007bff
            }
            @media (max-width: 1500px){
                    .container{
                        max-width: 99vw;
                        padding: 6px
                }
                    table{
                        min-width: 900px
                }
                    th,td{
                        font-size: 12px
                }
                    .search-container input[type="text"]{
                        width: 100px
                }
            }
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
                <button type="button" class="reset-button" onclick="window.location.href = window.location.pathname;">Reset</button>
            </form>
        </div>
        <!-- Create New Service Button -->
        <div style="text-align: left;">
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
                    <th class="actions-col">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if (empty($cleaningServices)) {
                    echo "<tr><td colspan='10' class='no-results'>No results found.</td></tr>";
                } else {
                    foreach ($cleaningServices as $service) {
                        $serviceId = $service->getServiceId();
                        $viewCount = $sviewController->getViewCount($serviceId);
                        $shortlistCount = $shortlistController->getShortlistCount($serviceId);
                        $price = $service->getPrice();
                        $category = $categoryController->getCategoryById($service->getCategoryId());

                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($serviceId) . "</td>";
                        echo "<td>" . htmlspecialchars($category ? $category->getName() : 'N/A') . "</td>";
                        echo "<td>" . htmlspecialchars($service->getTitle()) . "</td>";
                        echo "<td class='desc-cell'>" . htmlspecialchars($service->getDescription()) . "</td>";
                        echo "<td>$" . htmlspecialchars($price) . "</td>";
                        echo "<td>" . htmlspecialchars($service->getAvailability()) . "</td>";
                        echo "<td>" . ($service->isSuspended() ? 'Yes' : 'No') . "</td>";
                        echo "<td>" . htmlspecialchars($viewCount) . "</td>";
                        echo "<td>" . htmlspecialchars($shortlistCount) . "</td>";
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
        <div class="pagination">
            <?php
            $adjacents = 2;
            $start = max(1, $page - $adjacents);
            $end = min($totalPages, $page + $adjacents);
            $queryString = '';
            if ($searchQuery) $queryString .= '&search=' . urlencode($searchQuery);
            if ($accountId) $queryString .= '&account_id=' . urlencode($accountId);

            if ($page > 1) {
                echo '<a href="?page=1' . $queryString . '">First</a>';
                echo '<a href="?page=' . ($page - 1) . $queryString . '">Previous</a>';
            }
            for ($i = $start; $i <= $end; $i++) {
                if ($i == $page) {
                    echo '<span class="active">' . $i . '</span>';
                } else {
                    echo '<a href="?page=' . $i . $queryString . '">' . $i . '</a>';
                }
            }
            if ($page < $totalPages) {
                echo '<a href="?page=' . ($page + 1) . $queryString . '">Next</a>';
                echo '<a href="?page=' . $totalPages . $queryString . '">Last</a>';
            }
            ?>
        </div>
    </div>
</body>
</html>
