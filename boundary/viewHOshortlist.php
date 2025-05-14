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
        .container { background: white; padding: 30px; width: 100%; margin-top: 80px; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); box-sizing: border-box; }
        h1, h2 { margin-bottom: 20px; }
        .search-container {display: flex; flex-direction:column; margin-right: 20px;}
        .search-container input[type="text"] {padding: 8px;border: 1px solid #ccc;border-radius: 4px 0 0 4px;width: 300px; /* Adjust width as needed */font-family: Arial, sans-serif;}
        .search-button {
            padding: 10px 20px;
            background-color: #28a745; /* Bootstrap green */
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        .search-button:hover {
            background-color: #218838; /* darker green on hover */
        }
        .reset-button {padding: 10px 20px;background-color: #808080; color: white; border: none;border-radius: 4px;cursor: pointer;}
        .reset-button:hover {background-color: #565656;}
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 10px; text-align: left; }
        th { background-color: #f2f2f2; font-weight: bold; }
        .no-results { text-align: center; font-style: italic; color: #777; }
        .desc-cell { max-width: 300px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
        .action-links {
            display: flex;
            flex-direction: row;     /* Ensure horizontal layout */
            justify-content: center; /* Center horizontally in the cell */
            align-items: center;     /* Center vertically in the cell */
            gap: 8px;                /* Space between buttons */
            height: 100%;
        }
        .action-links a { margin-right: 8px; text-decoration: none; color: #007bff; }
        .action-links a:hover { text-decoration: underline; }
        .action-links a {
            display: flex;                /* Use flexbox for the link itself */
    align-items: center;          /* Center vertically */
    justify-content: center; 
            width: 140px;      /* Fixed width for all buttons */
            height: 50px;      /* Fixed height for all buttons */
            text-align: center;
            justify-content: center;
            padding: 8px 6px;
            border: none;
            border-radius: 4px;
            color: white !important;
            font-weight: bold;
            font-size: 1rem;
            text-decoration: none;
            background-color: #007bff;
            box-sizing: border-box;
            white-space: normal;      /* Allow text to wrap */
            word-break: break-word;   /* Break long words if needed */
            vertical-align: middle;
            line-height: normal;
        }

        .action-links a.view-details {
            background-color: #007bff;
        }
        .action-links a.view-details:hover {
            background-color: #0056b3;
        }

        .action-links a.add-shortlist {
            background-color: #28a745; /* green */
        }
        .action-links a.add-shortlist:hover {
            background-color: #218838;
        }

        .action-links a.remove-shortlist {
            background-color: #dc3545; /* red */
        }
        .action-links a.remove-shortlist:hover {
            background-color: #a71d2a;
        }

        .action-links a:hover {
            background-color: #0056b3;
            text-decoration: none;
        }

        .action-links a.remove-shortlist {
            background-color: #dc3545; /* Red for remove */
        }

        .action-links a.remove-shortlist:hover {
            background-color: #a71d2a;
        }
        
    </style>
</head>
<body>
    <div class="container">
        <!-- Search Form -->
        <div class="search-container">
            <h2>Search Shortlist</h2>
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
                            <a href='shortlistBooking.php?id=" . $service->shortlist_id . "' class = 'view-details'>View Details</a>
                            | <a href='removeShortlist.php?id=" . $service->service_id . "' class='remove-shortlist'>Remove</a>
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


