<?php
session_start();
if ($_SESSION['profileName'] !== 'Homeowner') {
    header('Location: ../login.php');
    exit();
}
require_once(__DIR__ . '/../homeownerNavbar.php');
require_once(__DIR__ . '/../controllers/UserAccountController.php');
require_once(__DIR__ . '/../controllers/ShortlistController.php');

// 1. Get shortlist ID from URL
$shortlistId = $_GET['id'] ?? null;
if (!$shortlistId) {
    echo "<p>Shortlist not specified.</p>";
    exit;
}

$homeownerAccountId = $_SESSION['user_id']; // or $_SESSION['accountId'], use your session variable

// 2. Get service_id from shortlist
$db = Database::getPDO();
$stmt = $db->prepare("SELECT service_id FROM service_shortlists WHERE shortlist_id = :shortlist_id");
$stmt->bindParam(':shortlist_id', $shortlistId, PDO::PARAM_INT);
$stmt->execute();
$shortlist = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$shortlist) {
    echo "<p>Shortlist not found.</p>";
    exit;
}
$serviceId = $shortlist['service_id'];

// 3. Get service details (including cleaner_account_id, category_id, etc.)
$stmt = $db->prepare("SELECT * FROM cleaner_services WHERE service_id = :service_id");
$stmt->bindParam(':service_id', $serviceId, PDO::PARAM_INT);
$stmt->execute();
$service = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$service) {
    echo "<p>Service not found.</p>";
    exit;
}

// 4. Get the cleaner's user account
$accountController = new UserAccountController();
$cleaner = $accountController->getUserById($service['cleaner_account_id']);

// 5. Get the category name (optional)
$stmt = $db->prepare("SELECT category_name FROM service_categories WHERE category_id = :category_id");
$stmt->bindParam(':category_id', $service['category_id'], PDO::PARAM_INT);
$stmt->execute();
$category = $stmt->fetch(PDO::FETCH_ASSOC);
$categoryName = $category ? $category['category_name'] : 'Unknown';

// 6. Prepare cleaner details
$cleanerName = $cleaner ? $cleaner->getFullName() : 'Not available';
$cleanerEmail = $cleaner ? $cleaner->getEmail() : 'Not available';

?>
<!DOCTYPE html>
<html>
<head>
    <title>Service Details</title>
    <style>
        .details-container { max-width: 600px; margin: 40px auto; background: #fff; padding: 30px; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); }
        .details-container h2 { margin-top: 0; }
        .details-row { margin-bottom: 18px; }
        .label { font-weight: bold; color: #333; }
        .message {
            padding: 10px;
            margin: 20px 0;
            border-radius: 5px;
            text-align: center;
        }
        .success {
            background-color: #28a745;  /* Green background for success */
            color: white;
        }
        .error {
            background-color: #ffc107;  /* Yellow background for warning */
            color: #856404;
        }
        .shortlist-button, .remove-button, .back-button {
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            color: white;
            text-decoration: none;
            margin-right: 10px;
            cursor: pointer;
        }
        .shortlist-button { background: #007bff; }
        .shortlist-button:hover { background: #0056b3; }
        .remove-button { background: #dc3545; }
        .remove-button:hover { background: #c82333; }
        .back-button { background: #6c757d; }
        .back-button:hover { background: #5a6268; }

        .book-button {
            background: #ff9800;        /* Bright orange */
            color: white;
            padding: 10px 24px;
            border: none;
            border-radius: 4px;
            text-decoration: none;
            margin-right: 10px;
            font-weight: bold;
            font-size: 16px;
            cursor: pointer;
            box-shadow: 0 2px 8px rgba(255,152,0,0.1);
            transition: background 0.2s, box-shadow 0.2s;
        }
        .book-button:hover {
            background: #fb8c00;        /* Slightly darker on hover */
            box-shadow: 0 4px 16px rgba(255,152,0,0.2);
            text-decoration: none;
        }

    </style>
</head>
<body>
<div class="details-container">
    <h2><?php echo htmlspecialchars($service['title']); ?></h2>
    <div class="details-row"><span class="label">Category:</span> <?php echo htmlspecialchars($categoryName); ?></div>
    <div class="details-row"><span class="label">Price:</span> $<?php echo number_format($service['price'],2); ?></div>
    <div class="details-row"><span class="label">Availability:</span> <?php echo htmlspecialchars($service['availability']); ?></div>
    <div class="details-row"><span class="label">Description:</span> <?php echo htmlspecialchars($service['description']); ?></div>
    <div class="details-row"><span class="label">Cleaner Name:</span> <?php echo htmlspecialchars($cleanerName); ?></div>
    <div class="details-row"><span class="label">Contact Email:</span> <?php echo htmlspecialchars($cleanerEmail); ?></div>
    <div style="margin-top:30px;">
        <a href="viewHOshortlist.php" class="back-button">Back to List</a>
        <a href="#" class="book-button">Book</a>
    </div>
</div>
</body>
</html>