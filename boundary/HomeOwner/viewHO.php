<?php
session_start();  // Start the session to access session variables
if ($_SESSION['profileName'] !== 'Homeowner') {
    header('Location: ../login.php');
    exit();
}
// Include dependencies
require_once __DIR__ . '/homeownerNavbar.php';
require_once __DIR__ . '/../../controllers/HomeOwner/ViewHOController.php';
require_once __DIR__ . '/../../controllers/HomeOwner/SearchHOController.php';
require_once __DIR__ . '/../../controllers/PlatformMgmt/ServiceCategoryController.php';
require_once __DIR__ . '/../../controllers/ShortlistController.php';


//----------------------------------------Short List Controller
$message = "";
if (isset($_GET['success']) && $_GET['success'] == 1) {
    $message = "✅ Service successfully added to your shortlist!";
}
if (isset($_GET['error']) && $_GET['error'] === 'already_shortlisted') {
    $message = "⚠️ This service is already in your shortlist.";
}
if (isset($_GET['removed']) && $_GET['removed'] == 1) {
    $message = "✅ Service removed from your shortlist.";
}
//----------------------------------------------------------------


// Handle search query
$searchQuery = isset($_GET['search']) ? $_GET['search'] : '';
$displayedServices = [];



$homeownerAccountId = $_SESSION['user_id'];
$shortlistController = new ShortlistController();
$shortlistedServices = $shortlistController->getShortlistedServices($homeownerAccountId);

// Build an array of shortlisted service IDs for easy lookup
$shortlistedIds = array_map(function($svc) {
    return is_object($svc) ? $svc->service_id : $svc['service_id'];
}, $shortlistedServices);

$perPage = 10;
$page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
$offset = ($page - 1) * $perPage;

if ($searchQuery !== '') {
    $controller = new SearchHOCleaningServicesController();
    $result = $controller->searchHOCleaningServices($searchQuery, $perPage, $offset);
} else {
    $viewController = new ViewHOCleaningServicesController();
    $result = $viewController->viewHOCleaningServices($perPage, $offset);
}
$displayedServices = $result['data'];
$total = $result['total'];
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
            padding: 20px
        }
        .container{
            background: white;
            padding: 20px;
            max-width: 100vw;
            margin: 0 auto;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            box-sizing: border-box;
            overflow-x: hidden;
            /* Prevent horizontal scroll */
        }
        h1,h2{
            margin-bottom: 20px
        }
        .search-container{
            display: flex;
            flex-direction: column;
            margin-right: 20px
        }
        .search-container input[type="text"]{
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 4px 0 0 4px;
            width: 300px;
            /* Adjust width as needed */
            font-family: Arial,sans-serif
        }
        .search-button{
            padding: 10px 20px;
            background-color: #28a745;
            /* Bootstrap green */
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer
        }
        .search-button:hover{
            background-color: #218838;
            /* darker green on hover */
        }
        .reset-button{
            padding: 10px 20px;
            background-color: #808080;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer
        }
        .reset-button:hover{
            background-color: #565656
        }
        table{
            width: 100%;
            border-collapse: collapse;
            table-layout: auto;
            min-width: 0 !important
        }
        th,td{
            border: 1px solid #ddd;
            padding: 10px;
            text-align:left;
            white-space: normal;
            word-break: break-word
        }
        .desc-cell{
            white-space: normal;
            word-break: break-word;
            max-width: none
        }
        .action-links{
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            justify-content: center
        }
        .action-links a{
            flex: 1 1 auto;
            min-width: 80px;
            padding: 8px 10px;
            text-align: center;
            white-space: normal;
            word-break: break-word;
            border-radius: 4px;
            color: white !important;
            font-weight: bold;
            font-size: 1rem;
            background-color: #007bff;
            text-decoration: none;
            box-sizing: border-box;
            transition: background 0.2s
        }
        .action-links a.view-details{
            background-color: #007bff
        }
        .action-links a.view-details:hover{
            background-color: #0056b3
        }
        .action-links a.add-shortlist{
            background-color: #28a745
        }
        .action-links a.add-shortlist:hover{
            background-color: #218838
        }
        .action-links a.remove-shortlist{
            background-color: #dc3545
        }
        .action-links a.remove-shortlist:hover{
            background-color: #a71d2a
        }
        .message{
            padding: 10px;
            margin: 20px 0;
            border-radius: 5px;
            text-align: center
        }
        .success{
            background-color: #28a745;
            color: white
        }
        .error{
            background-color: #ffc107;
            color: #856404
        }
        .shortlist-button,.remove-button,.back-button{
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            color: white;
            text-decoration: none;
            margin-right: 10px;
            cursor: pointer
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
        @media (max-width: 900px){
                .container{
                    padding: 10px
            }
                table,th,td{
                    font-size: 12px
            }
                .desc-cell{
                    max-width: 150px
            }
                .action-links a{
                    min-width: 70px;
                    font-size: 0.9rem
            }
        }
    </style>
</head>
<body>
    <div class="container">
    <?php if ($message): ?>
        <div class="message <?php echo (strpos($message, 'successfully') !== false || strpos($message, 'removed') !== false) ? 'success' : 'error'; ?>">
            <?php echo $message; ?>
        </div>
    <?php endif; ?>
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
                    echo "<tr><td colspan='7' class='no-results'>No results found.</td></tr>";
                } else {
                    foreach ($displayedServices as $service) {
                        // Numeric error safeguard
                        $price = $service->getPrice();
                        $priceValue = is_numeric($price) ? (float)$price : 0.00;

                        $categoryController = new ServiceCategoryController();
                        $category = $categoryController->getCategoryById($service->getCategoryId());


                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($service->getServiceId()) . "</td>";
                        echo "<td>" . htmlspecialchars($category->getName()) . "</td>";
                        echo "<td>" . htmlspecialchars($service->getTitle()) . "</td>";
                        echo "<td class='desc-cell'>" . htmlspecialchars($service->getDescription()) . "</td>";
                        echo "<td>$" . htmlspecialchars($price) . "</td>";
                        echo "<td>" . htmlspecialchars($service->getAvailability()) . "</td>";
                        echo "<td class='action-links'>";
                        // Correctly concatenate the URL parameter:
                        echo "<a href='viewHOCleanerDetails.php?service_id=" . urlencode($service->getServiceId()) . "' class='view-details' title='View Cleaner Profile & Services'>View Details</a>";
                        if (in_array($service->getServiceId(), $shortlistedIds)) {
                            echo "<a href='removeShortlist.php?id=" . $service->getServiceId() . "' class='remove-shortlist'>Remove from Shortlist</a>";
                        } else {
                            echo "<a href='addShortlist.php?id=" . $service->getServiceId() . "' class='add-shortlist'>Add to Shortlist</a>";
                        }
                        echo "</td>";
                        echo "</tr>";
                    }
                }
                ?>
            </tbody>
        </table>
        <div class="pagination" style="margin-top: 20px; text-align: center;">
    <?php
    $adjacents = 2;
    $start = max(1, $page - $adjacents);
    $end = min($totalPages, $page + $adjacents);
    $queryString = $searchQuery !== '' ? '&search=' . urlencode($searchQuery) : '';

    if ($page > 1) {
        echo '<a href="?page=1' . $queryString . '">First</a>';
        echo '<a href="?page=' . ($page - 1) . $queryString . '">Previous</a>';
    }
    for ($i = $start; $i <= $end; $i++) {
        if ($i == $page) {
            echo '<span class="active" style="background:#007bff;color:#fff;border-color:#007bff;padding:8px 16px;margin:0 4px;border-radius:4px;">' . $i . '</span>';
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
