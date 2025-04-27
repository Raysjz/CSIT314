<?php
session_start();
include('includes/header.php');
?>
<div class="container">
    <h2>Available Cleaning Services</h2>
    <div class="top-bar">
        <div class="search-container">
            <form method="get" action="" style="display: inline-block;">
                <input type="text" name="query" placeholder="Search services...">
                <input type="submit" value="Search">
            </form>
        </div>
    </div>
    <div class="service-list">
        <?php
        // Hardcoded array of services
        $allServices = [
            ['id' => 1, 'user' => 'John Doe', 'name' => 'Sparkle Clean', 'description' => 'House Cleaning', 'price_range' => '$100 - $150', 'views' => 5, 'shortlist' => 2],
            ['id' => 2, 'user' => 'Jane Smith', 'name' => 'Eco Clean', 'description' => 'Deep Cleaning', 'price_range' => '$120 - $180', 'views' => 10, 'shortlist' => 5],
            ['id' => 3, 'user' => 'Peter Jones', 'name' => 'Fresh Start', 'description' => 'Laundry', 'price_range' => '$80 - $120', 'views' => 3, 'shortlist' => 1],
            ['id' => 4, 'user' => 'Mary Brown', 'name' => 'Shine Bright', 'description' => 'House Cleaning', 'price_range' => '$90 - $140', 'views' => 7, 'shortlist' => 3],
        ];

        $displayedServices = $allServices;

        if (isset($_GET['query'])) {
            $searchQuery = strtolower(trim($_GET['query']));
            $displayedServices = [];

            foreach ($allServices as $service) {
                $name = strtolower($service['name']);
                $description = strtolower($service['description']);

                if (strpos($name, $searchQuery) !== false || strpos($description, $searchQuery) !== false) {
                    $displayedServices[] = $service;
                }
            }

            if (empty($displayedServices)) {
                echo "<p style='text-align: center;'>No services found matching your search query.</p>";
            }
        }

        if (!empty($displayedServices)) {
            foreach ($displayedServices as $service) {
                echo "<div class='service-card'>";
                echo "<div class='service-info'>";
                echo "<div class='placeholder-icon'></div> <div class='details'>";
                echo "<h3>" . htmlspecialchars($service['name']) . "</h3>";
                echo "<p>Offered by: " . htmlspecialchars($service['user']) . "</p>";
                echo "<p>" . htmlspecialchars($service['description']) . " - " . htmlspecialchars($service['price_range']) . "</p>";
                echo "</div></div>";
                echo "<div class='service-actions'>";
                echo "<div class='engagement'>";
                echo "<span><i class='fa fa-eye'></i> " . $service['views'] . "</span> ";
                echo "<span><i class='fa fa-heart'></i> " . $service['shortlist'] . "</span>";
                echo "</div>";
                echo "<div class='buttons'>";
                echo "<a href='view_service_details.php?id=1'>View Details</a>";
                echo "<a href='shortlist_service.php?id=" . $service['id'] . "' class='shortlist-button'>Add to Shortlist</a>";
                echo "</div>";
                echo "</div>";
                echo "</div>";
            }
        } else {
            echo "<p style='text-align: center;'>No services available.</p>";
        }
        ?>
    </div>
</div>
<?php include('includes/footer.php'); ?>
