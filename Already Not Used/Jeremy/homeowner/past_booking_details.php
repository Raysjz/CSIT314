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

// Get service ID from URL
$serviceId = isset($_GET['id']) ? intval($_GET['id']) : 0;
$service = null;

foreach ($pastBookings as $booking) {
    if ($booking['id'] === $serviceId) {
        $service = $booking;
        break;
    }
}
?>

<div class="container">
<a href="view_home_owner.php" class="back-button">← Back to Services</a>
    <?php if ($service): ?>
        <div class="service-details-card">
            <h2 style="font-weight: bold;"><?php echo htmlspecialchars($service['name']); ?></h2>
            <p style="color: #888; font-style: italic; margin-bottom: 10px;"><?php echo htmlspecialchars($service['type']); ?></p>

            <p><strong>Location:</strong> <?php echo htmlspecialchars($service['location']); ?></p>
            <p><strong>Price Range:</strong> <?php echo htmlspecialchars($service['price_range']); ?></p>
            <p><strong>Rating:</strong> <?php echo htmlspecialchars($service['rating']); ?> / 5</p>
            <p><strong>Availability:</strong> <?php echo htmlspecialchars($service['availability']); ?></p>
            <p><strong>Services Offered:</strong> <?php echo htmlspecialchars($service['services_offered']); ?></p>
            <p><strong>Contact:</strong> <?php echo htmlspecialchars($service['contact']); ?></p>

            <div class="service-actions">
                <div class="buttons">
                    <button class="create-button" onclick="alert('Booking functionality coming soon!')">Book</button>
                    <button class="create-button" onclick="alert('Leave a review functionality coming soon!')">Leave a Review</button>
                    <button class="create-button" onclick="alert('Added to shortlist!')">Add to Shortlist</button>
                </div>
            </div>
        </div>
    <?php else: ?>
        <p style="text-align: center;">Service not found.</p>
    <?php endif; ?>
</div>

<?php include('includes/footer.php'); ?>
