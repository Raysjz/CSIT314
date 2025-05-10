<?php
// Cleaner Update Cleaning Service

session_start(); // Start session

if ($_SESSION['profileName'] !== 'Cleaner') {
    header('Location: ../login.php');
    exit();
}

// Include dependencies
require_once __DIR__ . '/cleanerNavbar.php';
require_once __DIR__ . '/../../controllers/Cleaner/UpdateCSController.php';
require_once __DIR__ . '/../../controllers/PlatformMgmt/ServiceCategoryController.php';

// Get service ID from query
$serviceID = isset($_GET['serviceid']) ? $_GET['serviceid'] : null;
$controller = new UpdateCleaningServiceController();
$service = $controller->getCleaningServiceById($serviceID);

$serviceController = new ServiceCategoryController();
$categories = $serviceController->getAllCategories();

if (!$service) {
    echo "❌ No service found with ID: " . htmlspecialchars($serviceID);
    exit;
}

// Handle flash messages
$message = "";
$messageClass = "";
if (isset($_SESSION['flash_success'])) {
    $message = $_SESSION['flash_success'];
    $messageClass = "success";
    unset($_SESSION['flash_success']);
} else if (isset($_SESSION['flash_error'])) {
    $message = $_SESSION['flash_error'];
    $messageClass = "error";
    unset($_SESSION['flash_error']);
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $data = [
        'service_id' => $_POST['service_id'],
        'cleaner_account_id' => $_SESSION['user_id'],
        'category_id' => $_POST['category_id'],
        'title' => $_POST['title'],
        'description' => $_POST['description'],
        'price' => $_POST['price'],
        'availability' => $_POST['availability'],
        'is_suspended' => isset($_POST['is_suspended']) ? $_POST['is_suspended'] : false,
    ];

    $result = $controller->updateCleaningService($data);

    if ($result) {
        $_SESSION['flash_success'] = "✅ Service successfully updated!";
    } else {
        $_SESSION['flash_error'] = "❌ Error updating service.";
    }
    header("Location: updateCS.php?serviceid=" . urlencode($serviceID));
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Update Cleaning Service</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f4f4f4; margin: 0; padding: 40px; }
        .container { background: #fff; padding: 30px; max-width: 500px; margin: 80px auto 0; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); }
        h1 { margin-bottom: 20px; }
        label { display: block; margin-top: 15px; }
        input, select, textarea { width: 100%; padding: 10px; margin-top: 5px; border-radius: 4px; border: 1px solid #ccc; }
        .message { padding: 10px; margin: 20px 0; border-radius: 5px; text-align: center; }
        .success { background-color: #28a745; color: white; }
        .error { background-color: #dc3545; color: white; }
        .button-container { display: flex; justify-content: space-between; margin-top: 20px; }
        .back-button, .update-button { padding: 10px 20px; border: none; color: white; border-radius: 4px; cursor: pointer; text-decoration: none; }
        .back-button { background: #6c757d; }
        .back-button:hover { background: #5a6268; }
        .update-button { background: #28a745; }
        .update-button:hover { background: #218838; }
    </style>
</head>
<body>
<div class="container">
    <h1>Update Cleaning Service</h1>
    <?php if ($message): ?>
        <div class="message <?php echo $messageClass; ?>">
            <?php echo $message; ?>
        </div>
    <?php endif; ?>
    <form action="updateCS.php?serviceid=<?php echo htmlspecialchars($service->getServiceId()); ?>" method="post">
        <input type="hidden" name="service_id" value="<?php echo htmlspecialchars($service->getServiceId()); ?>">

        <label for="title">Title</label>
        <input type="text" id="title" name="title" value="<?php echo htmlspecialchars($service->getTitle()); ?>" required>

        <label for="category_id">Category</label>
        <select id="category_id" name="category_id" required>
            <option value="">-- Select Category --</option>
            <?php
            $currentCategoryId = $service->getCategoryId();
            foreach ($categories as $category) {
                $selected = ($category['category_id'] == $currentCategoryId) ? 'selected' : '';
                echo "<option value='" . htmlspecialchars($category['category_id']) . "' $selected>" . htmlspecialchars($category['category_name']) . "</option>";
            }
            ?>
        </select>

        <label for="description">Description</label>
        <textarea id="description" name="description" required><?php echo htmlspecialchars($service->getDescription()); ?></textarea>

        <label for="price">Price</label>
        <input type="number" id="price" name="price" value="<?php echo htmlspecialchars($service->getPrice()); ?>" required step="0.01" min="0">

        <label for="availability">Availability</label>
        <input type="text" id="availability" name="availability" value="<?php echo htmlspecialchars($service->getAvailability()); ?>" required>

        <label for="is_suspended">Is Suspended</label>
        <select id="is_suspended" name="is_suspended">
            <option value="1" <?php echo $service->isSuspended() ? 'selected' : ''; ?>>Yes</option>
            <option value="0" <?php echo !$service->isSuspended() ? 'selected' : ''; ?>>No</option>
        </select>

        <div class="button-container">
            <a href="viewCS.php" class="back-button">Back</a>
            <button type="submit" class="update-button">Update Service</button>
        </div>
    </form>
</div>
</body>
</html>
