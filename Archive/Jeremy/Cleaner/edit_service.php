<?php
include('includes/header.php');

// **1. Retrieve the Service Details**
// In a real application, you would get the service ID from the URL (e.g., $_GET['id'])
// and then fetch the service details from your database based on that ID.
// For this hardcoded example, let's assume the ID is passed in the URL.
$serviceIdToEdit = isset($_GET['id']) ? $_GET['id'] : null;
$serviceToEdit = null;

// **Important:** Replace this with your actual way of storing and retrieving services
$allServices = [
    ['id' => 1, 'name' => 'Sparkle Clean', 'description' => 'House Cleaning', 'price_range' => '$100 - $150', 'availability' => 'Weekly', 'contact_info' => '555-1212'],
    ['id' => 2, 'name' => 'Eco Clean', 'description' => 'Deep Cleaning', 'price_range' => '$120 - $180', 'availability' => 'Once', 'contact_info' => '123-2345'],
    ['id' => 3, 'name' => 'Fresh Start', 'description' => 'Laundry', 'price_range' => '$80 - $120', 'availability' => 'Once', 'contact_info' => '111-2222'],
    ['id' => 4, 'name' => 'Shine Bright', 'description' => 'House Cleaning', 'price_range' => '$90 - $140', 'availability' => 'Once', 'contact_info' => '555-6666'],
    // ... more services
];

if ($serviceIdToEdit !== null) {
    foreach ($allServices as $service) {
        if ($service['id'] == $serviceIdToEdit) {
            $serviceToEdit = $service;
            break;
        }
    }
}

// If the service ID is not valid, you might want to display an error message
if (!$serviceToEdit) {
    echo "<div class='container'><p class='error'>Service not found.</p><a href='my_services.php' class='back-button'>Back to My Services</a></div>";
    include('includes/footer.php');
    exit();
}
?>

<div class="container">
    <a href="my_services.php" class="back-button">Back to My Services</a>
    <h2>Edit Service</h2>
    <div class="form-container">
        <form action="edit_service_process.php" method="post">
            <input type="hidden" name="service_id" value="<?php echo htmlspecialchars($serviceToEdit['id']); ?>">
            <div class="form-group">
                <label for="service_name">Service Name:</label>
                <input type="text" id="service_name" name="service_name" value="<?php echo htmlspecialchars($serviceToEdit['name']); ?>" required>
            </div>
            <div class="form-group">
                <label for="type">Service Type:</label>
                <select id="type" name="type" required>
                    <option value="" disabled>Select a type</option>
                    <option value="house cleaning" <?php if ($serviceToEdit['description'] == 'House Cleaning') echo 'selected'; ?>>House Cleaning</option>
                    <option value="deep cleaning" <?php if ($serviceToEdit['description'] == 'Deep Cleaning') echo 'selected'; ?>>Deep Cleaning</option>
                    <option value="laundry" <?php if ($serviceToEdit['description'] == 'Laundry') echo 'selected'; ?>>Laundry</option>
                </select>
            </div>
            <div class="form-group">
                <label for="price">Price ($):</label>
                <input type="text" id="price" name="price" value="<?php echo htmlspecialchars($serviceToEdit['price_range']); ?>" required>
            </div>
            <div class="form-group">
                <label for="availability">Availability:</label>
                <input type="text" id="availability" name="availability" value="<?php echo htmlspecialchars($serviceToEdit['availability']); ?>" required>
            </div>
            <div class="form-group">
                <label for="contact_info">Contact Info:</label>
                <input type="text" id="contact_info" name="contact_info" value="<?php echo htmlspecialchars($serviceToEdit['contact_info']); ?>" required>
            </div>
            <input type="submit" value="Update Service">
        </form>
    </div>
</div>
<?php include('includes/footer.php'); ?>