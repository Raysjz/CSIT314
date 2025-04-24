<?php include('includes/header.php'); ?>
<div class="container">
    <a href="my_services.php" class="back-button">Back to My Services</a>
    <h2>Create New Cleaning Service</h2>
    <div class="form-container">
        <form action="create_service_process.php" method="post">
            <div class="form-group">
                <label for="service_name">Service Name:</label>
                <input type="text" id="service_name" name="service_name" placeholder="e.g. Sparkle Clean" required>
            </div>
            <div class="form-group">
                <label for="type">Service Type:</label>
                <select id="type" name="type" required>
                    <option value="" selected></option>
                    <option value="House Cleaning">House Cleaning</option>
                    <option value="Deep Cleaning">Deep Cleaning</option>
                    <option value="Laundry">Laundry</option>
                </select>
            </div>
            <div class="form-group">
                <label for="price_range">Price Range:</label>
                <input type="text" id="price_range" name="price_range" placeholder="e.g. $100 - $150" required>
            </div>
            <div class="form-group">
                <label for="availability">Availability:</label>
                <input type="text" id="availability" name="availability" placeholder="e.g. Mon-Fri, 9 AM - 5 PM" required>
            </div>
            <div class="form-group">
                <label for="contact_info">Contact Info:</label>
                <input type="text" id="contact_info" name="contact_info" placeholder="e.g. (555) 123-4567" required>
            </div>
            <input type="submit" value="Create Service">
        </form>
    </div>
</div>
<?php include('includes/footer.php'); ?>