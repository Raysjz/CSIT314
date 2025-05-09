<?php 
session_start();
if ($_SESSION['profileName'] !== 'Cleaner') {
    header('Location: ../login.php');
    exit();
}


// Include dependencies
require_once __DIR__ . '/cleanerNavbar.php';
require_once __DIR__ . '/../../controllers/Cleaner/CreateCSController.php';
require_once __DIR__ . '/../../controllers/PlatformMgmt/ServiceCategoryController.php';

$Servicecontroller = new ServiceCategoryController();
// Fetch profiles for the dropdown
$categories = $Servicecontroller->getAllCategories();  // Get all profiles from the database

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $data = [
        'cleaner_account_id' => $_SESSION['user_id'],
        'title' => $_POST['service_name'],
        'category_id' => $_POST['category_id'], // You may want to map this to an integer/category table
        'price' => $_POST['price'],
        'description' => $_POST['description'],
        'availability' => $_POST['availability'],
        'is_suspended' => false // New services are not suspended by default
    ];

    $controller = new CreateCleaningServiceController();
    $result = $controller->handleFormSubmission($data);

    if ($result === true) {
        $message = "✅ Cleaning service created successfully!";
    } else {
        $message = "❌ " . htmlspecialchars($result);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Create New Cleaning Service</title>
    <style>
        body { font-family: Arial; background: #f4f4f4; margin: 0; padding: 40px; }
        .container { background: white; padding: 30px; max-width: 500px; margin: auto; margin-top: 80px; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); }
        h1 { margin-bottom: 20px; }
        label { display: block; margin-top: 15px; }
        input, select, textarea { width: 100%; padding: 10px; margin-top: 5px; border-radius: 4px; border: 1px solid #ccc; }
        textarea { resize: vertical; min-height: 60px; }
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
            background-color: #dc3545;  /* Red background for error */
            color: white;
        }
        .button-container { display: flex; justify-content: space-between; margin-top: 20px; }
        .back-button, .create-button { padding: 10px 20px; border: none; color: white; border-radius: 4px; cursor: pointer; text-decoration: none; }
        .back-button { background: #6c757d; }
        .back-button:hover { background: #5a6268; }
        .create-button { background: #28a745; }
        .create-button:hover { background: #218838; }
    </style>
</head>
<body>

<div class="container">
    <h1>Create Cleaning Service</h1>

    <!-- Display message if available -->
    <?php if (isset($message) && $message): ?>
        <div class="message <?php echo (strpos($message, '❌') !== false) ? 'error' : 'success'; ?>">
            <?php echo $message; ?>
        </div>
    <?php endif; ?>

    <form id="createForm" action="createCS.php" method="post">
        <label for="service_name">Service Name</label>
        <input type="text" id="service_name" name="service_name" required>

        <label for="category">Category</label>
        <select id="category_id" name="category_id" required onchange="updateCategoryName()">
            <option value="">-- Select Category --</option>
            <?php
            // Dynamically populate category options from the database
            foreach ($categories as $category) {
                echo "<option value='" . htmlspecialchars($category['category_id']) . "'>" . htmlspecialchars($category['category_name']) . "</option>";
            }
            ?>
        </select>
        <input type="hidden" id="category_name" name="category_name">


        <label for="description">Description</label>
        <textarea id="description" name="description" placeholder="Describe your service" required></textarea>

        <label for="price">Price ($)</label>
        <input type="number" id="price" name="price" placeholder="e.g. 120" required min="0" step="0.01">

        <label for="availability">Availability</label>
        <input type="text" id="availability" name="availability" placeholder="e.g. Mon-Fri, 9 AM - 5 PM" required>

        <div class="button-container">
            <a href="viewCS.php" class="back-button">Back</a>
            <button type="submit" class="create-button">Create Service</button>
        </div>
    </form>

    <script>
        function updateCategoryName() {
            var categorySelect = document.getElementById('category_id');
            var categoryName = categorySelect.options[categorySelect.selectedIndex].text;
            document.getElementById('category_name').value = categoryName;
        }
        // Optionally, set the category name on page load (in case of edit forms)
        window.onload = updateCategoryName;
        </script>

</div>

</body>
</html>
