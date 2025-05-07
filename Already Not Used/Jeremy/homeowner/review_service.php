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
    <a href="view_service_details.php?id=<?php echo $serviceId; ?>" class="back-button">← Back to Service Details</a>

    <h2 style="font-weight: bold;"><?php echo htmlspecialchars($service['name']); ?></h2>
    <p style="color: #888; margin-top: -10px;"><?php echo htmlspecialchars($service['type']); ?></p>

    <form method="post" action="submit_review.php" style="margin-top: 20px;">
        <h3>Leave a Review</h3>
        <textarea name="review" rows="5" placeholder="Write your review here..." style="width: 100%; padding: 10px; font-size: 16px;"></textarea>
        <br><br>
        <button type="submit" class="create-button">Submit Review</button>
    </form>
</div>

<?php include('includes/footer.php'); ?>
