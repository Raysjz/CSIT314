<?php
session_start();
if ($_SESSION['profileName'] !== 'Cleaner') {
    header('Location: ../login.php');
    exit();
}
require_once(__DIR__ . '/../cleanerNavbar.php');
require_once(__DIR__ . '/../controllers/UserAccountController.php');
require_once(__DIR__ . '/../controllers/PlatformCategoryController.php');
require_once(__DIR__ . '/../controllers/viewCleanerMatchesController.php');
require_once(__DIR__ . '/../controllers/SearchCleanerMatchesController.php');


// Get filter values
$accountId = $_SESSION['user_id'] ?? null;
$categoryId = (isset($_GET['category_id']) && $_GET['category_id'] !== '') ? $_GET['category_id'] : null;
$startDate = (isset($_GET['start_date']) && $_GET['start_date'] !== '') ? $_GET['start_date'] : null;
$endDate = (isset($_GET['end_date']) && $_GET['end_date'] !== '') ? $_GET['end_date'] : null;

$viewController = new ViewCleanerMatchesController();

if (
    empty($categoryId) &&
    empty($startDate) &&
    empty($endDate)
) {
    // No filters: show all completed bookings
    $bookings = $viewController->viewCleanerMatches($accountId);
} else {
    // Filters: use the search controller
    $searchController = new SearchCleanerMatchesController();
    $bookings = $searchController->searchCleanerMatches(
        $accountId,
        $categoryId,
        $startDate,
        $endDate
    );
}



// Get all categories for dropdown
$userAccountController = new UserAccountController();
$categoryController = new PlatformCategoryController();
$categories = $categoryController->getAllCategories();



?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>View Bookings</title>
    <style>
        body { font-family: Arial; background: #f4f4f4; margin: 0; padding: 40px; }
        .container { background: white; padding: 30px; width: 100%; margin-top: 80px; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); box-sizing: border-box; }
        h1 { margin-bottom: 20px; }
        .search-container { margin-bottom: 20px; text-align: center; }
        .search-input { padding: 10px; border: 1px solid #ddd; border-radius: 4px; width: 60%; margin-bottom: 10px; }
        .search-button { padding: 10px 20px; background-color: #28a745; color: white; border: none; border-radius: 4px; cursor: pointer; }
        .search-button:hover { background-color: #0056b3; }
        .reset-button { padding: 10px 20px; background-color: #808080; color: white; border: none; border-radius: 4px; cursor: pointer; }
        .reset-button:hover { background-color: #565656; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 10px; text-align: left; }
        th { background-color: #f2f2f2; font-weight: bold; }
        .no-results { text-align: center; font-style: italic; color: #777; }
        .actions-buttons {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 8px;
        }
        
    </style>
</head>
<body>
<div class="container">

    <!-- Search Form -->
    <div class="search-container">
        <h2>Search Bookings</h2>
        <!-- Filter/Search Form -->
        <form method="get" style="margin-bottom: 20px;">
            <select name="category_id">
                <option value="">All Categories</option>
                <?php foreach ($categories as $cat): ?>
                    <option value="<?= $cat['category_id'] ?>" <?= ($categoryId == $cat['category_id']) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($cat['category_name']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <input type="date" name="start_date" value="<?= htmlspecialchars($startDate) ?>">
            <input type="date" name="end_date" value="<?= htmlspecialchars($endDate) ?>">
            <button type="submit" class="search-button">Filter</button>
            <button type="button" class="reset-button"
                    onclick="window.location.href='<?= strtok($_SERVER['REQUEST_URI'], '?') ?>'">Reset</button>
        </form>
    </div>

    

    <h2>Bookings List</h2>
    <table>
    <thead>
    <tr>
        <th>Match ID</th>
        <th>Homeowner Name</th>
        <th>Service ID</th>
        <th>Category Name</th>
        <th>Booking Date</th>
        <th>Status</th>
    </tr>
</thead>
<tbody>
<?php if (empty($bookings)): ?>
    <tr><td colspan="9" class="no-results">No bookings found.</td></tr>
<?php else: foreach ($bookings as $booking): ?>
    <?php
        // Get Homeowner object
        $homeowner = $userAccountController->getUserById($booking->getHomeownerAccountId());
        $homeownerName = $homeowner ? $homeowner->getFullName() : "Unknown";

        // Get Category object
        $category = $categoryController->getCategoryById($booking->getCategoryId());
        $categoryName = $category ? $category->getName() : "Unknown";
    ?>
    <tr>
        <td><?= htmlspecialchars($booking->getMatchId()) ?></td>
        <td><?= htmlspecialchars($homeownerName) ?></td>
        <td><?= htmlspecialchars($booking->getServiceId()) ?></td>
        <td><?= htmlspecialchars($categoryName) ?></td>
        <td><?= htmlspecialchars($booking->getBookingDate()) ?></td>
        <td><?= htmlspecialchars($booking->getStatus()) ?></td>
    </tr>
<?php endforeach; endif; ?>
</tbody>



    </table>
</div>
</body>
</html>
