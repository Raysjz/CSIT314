<?php
session_start();
include('includes/header.php');

// Dummy hardcoded past bookings
$pastBookings = [
    [
        'id' => 1,
        'name' => 'Sparkle Clean',
        'type' => 'House Cleaning',
        'location' => 'Downtown LA',
        'price_range' => '$100 - $150',
        'date' => '2025-03-20',
        'contact' => 'john@example.com',
        'services_offered' => 'Basic cleaning, dusting, vacuuming',
        'availability' => 'Monday - Friday, 9 AM - 5 PM',
        'rating' => 4.5,
    ],
    [
        'id' => 2,
        'name' => 'Eco Clean',
        'type' => 'Deep Cleaning',
        'location' => 'Santa Monica',
        'price_range' => '$120 - $180',
        'date' => '2025-04-05',
        'contact' => 'jane@example.com',
        'services_offered' => 'Deep cleaning, eco-friendly products',
        'availability' => 'Weekends, 10 AM - 4 PM',
        'rating' => 4.8,
    ],
    [
        'id' => 3,
        'name' => 'Fresh Start',
        'type' => 'Laundry',
        'location' => 'Venice Beach',
        'price_range' => '$80 - $120',
        'date' => '2025-02-15',
        'contact' => 'peter@example.com',
        'services_offered' => 'Laundry wash, dry and fold',
        'availability' => 'Daily, 8 AM - 8 PM',
        'rating' => 4.3,
    ],
];

?>

<div class="container">
    
<a href="view_homeowner.php" class="back-button">← Back to Services</a>
    <h2>Past Bookings</h2>
    
    <div class="service-list">
        <?php
        if (!empty($pastBookings)) {
            foreach ($pastBookings as $booking) {
                echo "<div class='service-card'>";
                echo "<div class='service-info'>";
                echo "<div class='placeholder-icon'></div>";
                echo "<div class='details'>";
                echo "<h3>" . htmlspecialchars($booking['name']) . "</h3>";
                echo "<p style='color: #888; font-style: italic;'>" . htmlspecialchars($booking['type']) . "</p>";
                echo "<p>" . htmlspecialchars($booking['location']) . " | " . htmlspecialchars($booking['price_range']) . " | Rating: " . htmlspecialchars($booking['rating']) . "/5</p>";
                echo "<p><strong>Availability:</strong> " . htmlspecialchars($booking['availability']) . "</p>";
                echo "<p><strong>Services Offered:</strong> " . htmlspecialchars($booking['services_offered']) . "</p>";
                echo "<p><strong>Contact:</strong> " . htmlspecialchars($booking['contact']) . "</p>";
                echo "</div>";
                echo "</div>";
                
                // Add buttons with consistent layout
                echo "<div class='service-actions'>";
                echo "<div class='buttons'>";
                echo "<a href='past_booking_details.php?id=" . $booking['id'] . "' class='create-button'>View Details</a>";
                echo "</div>";
                echo "</div>";

                echo "</div>";
            }
        } else {
            echo "<p style='text-align: center;'>No past bookings found.</p>";
        }
        ?>
    </div>
</div>

<?php include('includes/footer.php'); ?>
