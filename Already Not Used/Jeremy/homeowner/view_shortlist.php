<?php
session_start();
include('includes/header.php');
?>

<div class="container">
<a href="view_home_owner.php" class="back-button">← Back to Services</a>
    <h2>My Shortlist</h2>

    <div class="service-list">
        <?php
        // Hardcoded shortlist examples
        $shortlistedServices = [
            [
                'id' => 1,
                'name' => 'Sparkle Clean',
                'type' => 'House Cleaning',
                'location' => 'Downtown LA',
                'price_range' => '$100 - $150',
                'rating' => 4.5,
                'availability' => 'Mon - Fri, 9am - 5pm',
                'services_offered' => 'Dusting, vacuuming, window cleaning',
                'contact' => 'john@example.com',
            ],
            [
                'id' => 2,
                'name' => 'Eco Clean',
                'type' => 'Deep Cleaning',
                'location' => 'Santa Monica',
                'price_range' => '$120 - $180',
                'rating' => 4.8,
                'availability' => 'Weekends, 10am - 6pm',
                'services_offered' => 'Eco-friendly supplies, kitchen deep clean',
                'contact' => 'jane@example.com',
            ],
            // Add more dummy services here if you want
        ];

        if (!empty($shortlistedServices)) {
            foreach ($shortlistedServices as $service) {
                echo "<div class='service-card'>";
                echo "<div class='service-info'>";
                echo "<div class='placeholder-icon'></div>";
                echo "<div class='details'>";
                echo "<h3>" . htmlspecialchars($service['name']) . "</h3>";
                echo "<p style='color: #888; font-style: italic;'>" . htmlspecialchars($service['type']) . "</p>";
                echo "<p>" . htmlspecialchars($service['location']) . " | " . htmlspecialchars($service['price_range']) . " | Rating: " . htmlspecialchars($service['rating']) . "/5</p>";
                echo "<p><strong>Availability:</strong> " . htmlspecialchars($service['availability']) . "</p>";
                echo "<p><strong>Services Offered:</strong> " . htmlspecialchars($service['services_offered']) . "</p>";
                echo "<p><strong>Contact:</strong> " . htmlspecialchars($service['contact']) . "</p>";
                echo "</div>";
                echo "</div>";

                echo "<div class='service-actions'>";
                echo "<div class='buttons'>";
                echo "<a href='view_service_details.php?id=" . $service['id'] . "'>View Details</a>";
                echo "<a href='#' class='suspend-button'>Remove</a>";
                echo "</div>";
                echo "</div>";

                echo "</div>";
            }
        } else {
            echo "<p style='text-align: center;'>Your shortlist is empty.</p>";
        }
        ?>
    </div>
</div>

<?php include('includes/footer.php'); ?>
