<?php
// View HO Bookings

session_start(); // Start session

// Redirect if not Homeowner
if ($_SESSION['profileName'] !== 'Homeowner') {
    header('Location: ../login.php');
    exit();
}

// Include dependencies
require_once __DIR__ . '/homeownerNavbar.php';
require_once __DIR__ . '/../../controllers/UserAdmin/UserAccountController.php';
require_once __DIR__ . '/../../controllers/PlatformMgmt/ServiceCategoryController.php';
require_once __DIR__ . '/../../controllers/HomeOwner/viewHOBookingController.php';
require_once __DIR__ . '/../../controllers/HomeOwner/SearchHOBookingController.php';
require_once __DIR__ . '/../../controllers/HomeOwner/HOBookingMiscController.php';

// Get filter values
$accountId = $_SESSION['user_id'] ?? null;
$categoryId = (isset($_GET['category_id']) && $_GET['category_id'] !== '') ? $_GET['category_id'] : null;
$startDate = (isset($_GET['start_date']) && $_GET['start_date'] !== '') ? $_GET['start_date'] : null;
$endDate = (isset($_GET['end_date']) && $_GET['end_date'] !== '') ? $_GET['end_date'] : null;

$perPage = 10;
$page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
$offset = ($page - 1) * $perPage;

// Get all categories for dropdown
$userAccountController = new UserAccountController();
$categoryController = new ServiceCategoryController();
$categories = $categoryController->getAllCategories();

$miscController = new HOBookingMiscController();

if (empty($categoryId) && empty($startDate) && empty($endDate)) {
    $viewController = new viewHomeOwnerBookingsController();
    $bookings = $viewController->viewHomeownerBookings($accountId, $perPage, $offset);
    $total = $miscController->countHomeownerBookings($accountId);
} else {
    $searchController = new searchHOBookingController();
    $bookings = $searchController->searchHOBooking($accountId, $categoryId, $startDate, $endDate, $perPage, $offset);
    $total = $miscController->countSearchHomeownerBookings($accountId, $categoryId, $startDate, $endDate);
}
$totalPages = ceil($total / $perPage);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>View Bookings</title>
    <style>
        body { font-family: Arial,sans-serif; background: #f4f4f4; margin: 0; padding: 0; }
        .container { background: white; padding: 30px; width: 100%; max-width: 1000px; margin: 20px auto 0 auto; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); box-sizing: border-box; overflow-x: hidden; }
        h1, h2 { margin-bottom: 20px; }
        .search-container { margin-bottom: 20px; text-align: center; }
        .search-input { padding: 10px; border: 1px solid #ddd; border-radius: 4px; width: 60%; margin-bottom: 10px; }
        .search-button { padding: 10px 20px; background-color: #28a745; color: white; border: none; border-radius: 4px; cursor: pointer; }
        .search-button:hover { background-color: #0056b3; }
        .reset-button { padding: 10px 20px; background-color: #808080; color: white; border: none; border-radius: 4px; cursor: pointer; }
        .reset-button:hover { background-color: #565656; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; table-layout: auto; }
        th, td { border: 1px solid #ddd; padding: 10px; text-align: left; white-space: normal; word-break: break-word; font-size: 1rem; }
        th { background-color: #f2f2f2; font-weight: bold; }
        .no-results { text-align: center; font-style: italic; color: #777; }
        .actions-buttons { display: flex; justify-content: center; align-items: center; gap: 8px; }
        .pagination { margin-top: 20px; text-align: center; }
        .pagination a, .pagination span { padding: 8px 16px; margin: 0 4px; border: 1px solid #ddd; text-decoration: none; color: #007bff; border-radius: 4px; transition: background 0.2s; }
        .pagination a:hover { background-color: #f2f2f2; }
        .pagination .active { background-color: #007bff; color: white; border-color: #007bff; }
        @media (max-width: 700px) {
            .container { max-width: 98vw; padding: 8px; }
            th, td { font-size: 12px; padding: 6px; }
            .search-input { width: 100%; }
        }
    </style>
</head>
<body>
<div class="container">

    <!-- Search Form -->
    <div class="search-container">
        <h2>Search Bookings</h2>
        <form method="get" style="margin-bottom: 20px;">
            <select name="category_id">
                <option value="">All Categories</option>
                <?php foreach ($categories as $cat): ?>
                    <option value="<?= (int)$cat['category_id'] ?>" <?= ($categoryId == $cat['category_id']) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($cat['category_name']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <input type="date" name="start_date" value="<?= htmlspecialchars($startDate) ?>">
            <input type="date" name="end_date" value="<?= htmlspecialchars($endDate) ?>">
            <button type="submit" class="search-button">Filter</button>
            <button type="button" class="reset-button" onclick="window.location.href='<?= strtok($_SERVER['REQUEST_URI'], '?') ?>'">Reset</button>
        </form>
    </div>

    <h2>Bookings List</h2>
    <table>
        <thead>
            <tr>
                <th>Match ID</th>
                <th>Cleaner Name</th>
                <th>Service ID</th>
                <th>Category Name</th>
                <th>Booking Date</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
        <?php if (empty($bookings)): ?>
            <tr><td colspan="6" class="no-results">No bookings found.</td></tr>
        <?php else: foreach ($bookings as $booking): ?>
            <?php
                // Get Cleaner object
                $cleaner = $userAccountController->getUserById($booking->getCleanerAccountId());
                $cleanerName = $cleaner ? $cleaner->getFullName() : "Unknown";
                // Get Category object
                $category = $categoryController->getCategoryById($booking->getCategoryId());
                $categoryName = $category ? $category->getName() : "Unknown";
            ?>
            <tr>
                <td><?= htmlspecialchars($booking->getMatchId()) ?></td>
                <td><?= htmlspecialchars($cleanerName) ?></td>
                <td><?= htmlspecialchars($booking->getServiceId()) ?></td>
                <td><?= htmlspecialchars($categoryName) ?></td>
                <td><?= htmlspecialchars($booking->getBookingDate()) ?></td>
                <td><?= htmlspecialchars($booking->getStatus()) ?></td>
            </tr>
        <?php endforeach; endif; ?>
        </tbody>
    </table>
    <div class="pagination" style="margin-top: 20px; text-align: center;">
        <?php
        $queryString = "";
        if ($categoryId) $queryString .= "&category_id=" . urlencode($categoryId);
        if ($startDate) $queryString .= "&start_date=" . urlencode($startDate);
        if ($endDate) $queryString .= "&end_date=" . urlencode($endDate);

        $adjacents = 2;
        $start = max(1, $page - $adjacents);
        $end = min($totalPages, $page + $adjacents);

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
