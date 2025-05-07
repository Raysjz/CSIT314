<?php include('includes/header.php'); ?>
<div class="container">
    <h2>Confirmed Matches</h2>
    <div class="top-bar">
        <div class="search-container">
            <form method="get" action="match_history.php" style="display: inline-block;">
                <label for="type">Filter by Service Type:</label>
                <select id="type" name="type">
                    <option value="">All Types</option>
                    <option value="Deep Cleaning">Deep Cleaning</option>
                    <option value="Laundry">Laundry</option>
                    <option value="House Cleaning">House Cleaning</option>
                    </select>
                <label for="date" style="margin-left: 10px;">Filter by Date:</label>
                <input type="date" id="date" name="date" style="margin-left: 10px;">
                <input type="submit" value="Filter" style="margin-left: 10px;">
            </form>
        </div>
    </div>

    <h3>Past Bookings</h3>
    <div class="service-list">
        <?php
        // **Simulated Data - Replace with your actual booking data retrieval**
        $allPastBookings = [
            ['user_name' => 'John Doe', 'service_name' => 'Eco Clean', 'service_type' => 'Deep Cleaning', 'price' => '80', 'booking_date' => '2025-01-15'],
            ['user_name' => 'Jane Smith', 'service_name' => 'Fresh Start', 'service_type' => 'Laundry', 'price' => '120', 'booking_date' => '2025-02-03'],
            ['user_name' => 'Emily Johnson', 'service_name' => 'Shine Bright', 'service_type' => 'House Cleaning', 'price' => '180', 'booking_date' => '2025-03-10'],
            ['user_name' => 'Michael Brown', 'service_name' => 'Deep Clean', 'service_type' => 'Deep Cleaning', 'price' => '100', 'booking_date' => '2025-01-20'],
            ['user_name' => 'Jessica Davis', 'service_name' => 'Laundry Day', 'service_type' => 'Laundry', 'price' => '90', 'booking_date' => '2025-02-10'],
            ['user_name' => 'David Wilson', 'service_name' => 'Home Cleaning', 'service_type' => 'House Cleaning', 'price' => '150', 'booking_date' => '2025-03-01'],
        ];

        $pastBookings = $allPastBookings; // Initialize with all bookings

        // **Filtering Logic**
        if (isset($_GET['type']) && $_GET['type'] != '') {
            $selectedType = $_GET['type'];
            $tempBookings = [];
            foreach ($pastBookings as $booking) {
                if ($booking['service_type'] == $selectedType) {
                    $tempBookings[] = $booking;
                }
            }
            $pastBookings = $tempBookings; // Apply type filter
        }

        if (isset($_GET['date']) && $_GET['date'] != '') {
            $selectedDate = $_GET['date'];
            $tempBookings = [];
            foreach ($pastBookings as $booking) {
                if ($booking['booking_date'] == $selectedDate) {
                    $tempBookings[] = $booking;
                }
            }
            $pastBookings = $tempBookings; // Apply date filter
        }

        // **Display Results**
        if (empty($pastBookings)) {
            echo "<p style='text-align: center;'>No past bookings found matching your criteria.</p>";
        } else {
            foreach ($pastBookings as $booking): ?>
                <div class="service-card">
                    <div class="service-info">
                        <div class="placeholder-icon"></div>
                        <div class="details">
                            <h3><?php echo htmlspecialchars($booking['service_name']); ?> (<?php echo htmlspecialchars($booking['user_name']); ?>)</h3>
                            <p><?php echo htmlspecialchars($booking['service_type']); ?> - $<?php echo htmlspecialchars($booking['price']); ?> | <?php echo date('M j, Y', strtotime($booking['booking_date'])); ?></p>
                        </div>
                    </div>
                    <div class="service-actions">
                        <div class="buttons">
                            <a href="#">View Details</a>
                        </div>
                    </div>
                </div>
            <?php endforeach;
        }
        ?>
    </div>
</div>
<?php include('includes/footer.php'); ?>