<?php
session_start();
if ($_SESSION['profileName'] !== 'Cleaner') {
    header('Location: ../login.php');
    exit();
}
require_once(__DIR__ . '/../cleanerNavbar.php');
require_once(__DIR__ . '/../controllers/UpdateCSController.php');
require_once(__DIR__ . '/../controllers/PlatformCategoryController.php');

$serviceIdToUpdate = isset($_GET['serviceid']) ? $_GET['serviceid'] : null;
$controller = new UpdateCleaningServiceController();
$serviceToUpdate = $controller->getCleaningServiceById($serviceIdToUpdate);
$Platformcontroller = new PlatformCategoryController();
// Fetch profiles for the dropdown
$categories = $Platformcontroller->getAllCategories();  // Get all profiles from the database

if (!$serviceToUpdate) {
    echo "❌ No service found with ID: " . htmlspecialchars($serviceIdToUpdate);
    exit;
}

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $data = [
        'service_id' => $_POST['service_id'],
        'cleaner_account_id' => $_SESSION['user_id'], // Use session for security
        'category_id' => $_POST['category_id'],
        'title' => $_POST['title'],
        'description' => $_POST['description'],
        'price' => $_POST['price'],
        'availability' => $_POST['availability'],
        'is_suspended' => isset($_POST['is_suspended']) ? $_POST['is_suspended'] : false,
        // Optionally add created_at/updated_at if needed
    ];

    $result = $controller->updateCleaningService($data);

    if ($result) {
        $message = "✅ Service successfully updated!";
    } else {
        $message = "❌ Error updating service.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Update Cleaning Service</title>
    <style>
        body { font-family: Arial; background: #f4f4f4; margin: 0; padding: 40px; }
        .container { background: white; padding: 30px; max-width: 500px; margin: auto; margin-top: 80px; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); }
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
        <div class="message <?php echo (strpos($message, '❌') !== false) ? 'error' : 'success'; ?>">
            <?php echo $message; ?>
        </div>
    <?php endif; ?>
    <form action="updateCS.php?serviceid=<?php echo htmlspecialchars($serviceToUpdate->getServiceId()); ?>" method="post">
        <input type="hidden" name="service_id" value="<?php echo htmlspecialchars($serviceToUpdate->getServiceId()); ?>">

        <label for="title">Title</label>
        <input type="text" id="title" name="title" value="<?php echo htmlspecialchars($serviceToUpdate->getTitle()); ?>" required>

        <label for="category">Category</label>
        <select id="category_id" name="category_id" required onchange="updateCategoryName()">
            <option value="">-- Select Category --</option>
            <?php
            $currentCategoryId = $serviceToUpdate->getCategoryId();
            $currentCategoryName = '';
            foreach ($categories as $category) {
                $selected = ($category['category_id'] == $currentCategoryId) ? 'selected' : '';
                if ($selected) $currentCategoryName = $category['category_name'];
                echo "<option value='" . htmlspecialchars($category['category_id']) . "' $selected>" . htmlspecialchars($category['category_name']) . "</option>";
            }
            ?>
        </select>
        <input type="hidden" id="category_name" name="category_name" value="<?php echo htmlspecialchars($currentCategoryName); ?>">


        <label for="description">Description</label>
        <textarea id="description" name="description" required><?php echo htmlspecialchars($serviceToUpdate->getDescription()); ?></textarea>

        <label for="price">Price</label>
        <input type="number" id="price" name="price" value="<?php echo htmlspecialchars($serviceToUpdate->getPrice()); ?>" required step="0.01" min="0">

        <label for="availability">Availability</label>
        <input type="text" id="availability" name="availability" value="<?php echo htmlspecialchars($serviceToUpdate->getAvailability()); ?>" required>


        <label for="is_suspended">Is Suspended</label>
        <select id="is_suspended" name="is_suspended">
            <option value="1" <?php echo $serviceToUpdate->isSuspended() ? 'selected' : ''; ?>>Yes</option>
            <option value="0" <?php echo !$serviceToUpdate->isSuspended() ? 'selected' : ''; ?>>No</option>
        </select>

        <div class="button-container">
            <a href="viewCS.php" class="back-button">Back</a>
            <button type="submit" class="update-button">Update Service</button>
        </div>
    </form>

    <script>
    function updateCategoryName() {
        var categorySelect = document.getElementById('category_id');
        var categoryName = categorySelect.options[categorySelect.selectedIndex].text;
        document.getElementById('category_name').value = categoryName;
    }
    // Set the category name on page load (for edit forms)
    window.onload = updateCategoryName;
</script>

</div>
</body>
</html>
