<?php
session_start();
include('includes/header.php');

// Dummy service data (you can later fetch from DB or session)
$services = [
    1 => [
        'name' => 'Sparkle Clean',
        'type' => 'House Cleaning',
        'location' => 'Downtown LA',
        'price_range' => '$100 - $150',
        'rating' => 4.5,
        'availability' => 'Mon - Fri, 9am - 5pm',
        'services_offered' => 'Dusting, vacuuming, mopping, window cleaning',
        'contact' => 'john@example.com',
    ],
    2 => [
        'name' => 'Eco Clean',
        'type' => 'Deep Cleaning',
        'location' => 'Santa Monica',
        'price_range' => '$120 - $180',
        'rating' => 4.8,
        'availability' => 'Weekends only',
        'services_offered' => 'Eco-friendly deep cleaning with non-toxic products',
        'contact' => 'jane@example.com',
    ],
    // Add more services as needed
];

$serviceId = isset($_GET['id']) ? intval($_GET['id']) : 0;

if (!isset($_GET['id']) || !isset($services[$_GET['id']])) {
    echo "Service not found.";
    exit();
}

$serviceId = intval($_GET['id']);
$service = $services[$serviceId];
?>

<div class="container">
    <a href="view_homeowner.php" class="back-button">← Back to Services</a>

    <h2 style="font-weight: bold;"><?php echo htmlspecialchars($service['name']); ?></h2>
    <p style="color: #888; margin-top: -10px;"><?php echo htmlspecialchars($service['type']); ?></p>

    <p><strong>Location:</strong> <?php echo htmlspecialchars($service['location']); ?></p>
    <p><strong>Price Range:</strong> <?php echo htmlspecialchars($service['price_range']); ?></p>
    <p><strong>Rating:</strong> <?php echo htmlspecialchars($service['rating']); ?> / 5</p>
    <p><strong>Availability:</strong> <?php echo htmlspecialchars($service['availability']); ?></p>
    <p><strong>Services Offered:</strong> <?php echo htmlspecialchars($service['services_offered']); ?></p>
    <p><strong>Contact:</strong> <?php echo htmlspecialchars($service['contact']); ?></p>

    <div style="margin-top: 20px;">
        <button class="create-button" onclick="alert('Booking flow coming soon!')">Book</button>
        <a href="review_service.php?id=<?php echo $serviceId; ?>" class="create-button">Leave a Review</a>
        <form method="post" action="add_to_shortlist.php" style="display:inline;">
            <input type="hidden" name="service_id" value="<?php echo $serviceId; ?>">
            <button type="submit" class="create-button">Add to Shortlist</button>
        </form>
    </div>
</div>

<?php include('includes/footer.php'); ?>
