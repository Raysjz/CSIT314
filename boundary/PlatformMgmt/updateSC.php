<?php
// Update Service Category

session_start(); // Start session

// Redirect if not Platform Management
if ($_SESSION['profileName'] !== 'Platform Management') {
    header('Location: ../login.php');
    exit();
}

// Include dependencies
require_once __DIR__ . '/platformNavbar.php';
require_once __DIR__ . '/../../controllers/PlatformMgmt/UpdateScController.php';

// Get the category ID from the query parameter
$categoryId = isset($_GET['categoryid']) ? $_GET['categoryid'] : null;

// Instantiate the controller for fetching and updating category data
$controller = new UpdateServiceCategoryController();
$categoryToUpdate = $controller->getServiceCategoryById($categoryId);

// If no category is found, show an error message and stop
if (!$categoryToUpdate) {
    echo "❌ No category found with ID: " . htmlspecialchars($categoryId);
    exit;
}

// Initialize message variable
$message = "";

// Handle form submission for updating category data
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $data = [
        'id' => $_POST['id'],
        'name' => $_POST['name'],
        // Ensure the value is 1 or 0
        'isSuspended' => isset($_POST['is_suspended']) ? (int)$_POST['is_suspended'] : 0
    ];

    $result = $controller->updateServiceCategory($data);

    if ($result) {
        $message = "✅ Category successfully updated!";
    } else {
        $message = "❌ Error updating category.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Update Service Category</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f4f4f4; margin: 0; padding: 40px; }
        .container { background: #fff; padding: 30px; max-width: 500px; margin: 80px auto 0; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); }
        h1 { margin-bottom: 20px; }
        label { display: block; margin-top: 15px; font-weight: bold; }
        input, select { width: 100%; padding: 10px; margin-top: 5px; border-radius: 4px; border: 1px solid #ccc; box-sizing: border-box; }
        .message { padding: 10px; margin: 20px 0; border-radius: 5px; text-align: center; font-weight: bold; }
        .success { background-color: #28a745; color: #fff; }
        .error { background-color: #dc3545; color: #fff; }
        .button-container { display: flex; justify-content: space-between; margin-top: 20px; }
        .back-button, .update-button { padding: 10px 20px; border: none; color: #fff; border-radius: 4px; cursor: pointer; text-decoration: none; font-size: 1rem; }
        .back-button { background: #6c757d; }
        .back-button:hover { background: #5a6268; }
        .update-button { background: #28a745; }
        .update-button:hover { background: #218838; }
    </style>
</head>
<body>
<div class="container">
    <h1>Update Service Category</h1>

    <?php if ($message): ?>
        <div class="message <?php echo (strpos($message, '❌') !== false) ? 'error' : 'success'; ?>">
            <?php echo htmlspecialchars($message); ?>
        </div>
    <?php endif; ?>

    <form action="updateSC.php?categoryid=<?php echo htmlspecialchars($categoryToUpdate->getId()); ?>" method="post">
        <input type="hidden" name="id" value="<?php echo htmlspecialchars($categoryToUpdate->getId()); ?>">

        <label for="name">Service Category Name</label>
        <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($categoryToUpdate->getName()); ?>" required>

        <label for="is_suspended">Is Suspended</label>
        <select id="is_suspended" name="is_suspended">
            <option value="1" <?php echo $categoryToUpdate->getIsSuspended() ? 'selected' : ''; ?>>Yes</option>
            <option value="0" <?php echo !$categoryToUpdate->getIsSuspended() ? 'selected' : ''; ?>>No</option>
        </select>

        <div class="button-container">
            <a href="viewSC.php" class="back-button">Back</a>
            <button type="submit" class="update-button">Update Category</button>
        </div>
    </form>
</div>
</body>
</html>
