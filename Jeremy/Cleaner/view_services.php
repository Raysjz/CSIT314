<?php
session_start();

// Handle service suspension
if (isset($_GET['suspend_id']) && is_numeric($_GET['suspend_id'])) {
    $suspendId = intval($_GET['suspend_id']);
    $serviceRemoved = false;

    if (isset($_SESSION['services'])) {
        foreach ($_SESSION['services'] as $key => $service) {
            if (is_array($service) && isset($service['id']) && intval($service['id']) === $suspendId) {
                unset($_SESSION['services'][$key]);
                $_SESSION['success_message'] = "Service '" . htmlspecialchars($service['name']) . "' has been suspended.";
                $_SESSION['services'] = array_values($_SESSION['services']); // Re-index the array
                $serviceRemoved = true;
                break; // Important: Exit the loop after removing the service
            }
        }

        if (!$serviceRemoved) {
            $_SESSION['error_message'] = "Service with ID " . $suspendId . " not found.";
        }
    } else {
        $_SESSION['error_message'] = "No services in session.";
    }
    header("Location: view_services.php"); // Redirect after processing suspension
    exit();
}

// Display success/error messages
if (isset($_SESSION['error_message'])) {
    echo "<p class='error'>" . $_SESSION['error_message'] . "</p>";
    unset($_SESSION['error_message']);
}
if (isset($_SESSION['success_message'])) {
    echo "<p class='success'>" . $_SESSION['success_message'] . "</p>";
    unset($_SESSION['success_message']);
}

include('includes/header.php');
?>
<div class="container my-services-container">
    <h2>My Cleaning Services</h2>
    <div class="top-bar">
        <div class="search-container">
            <form method="get" action="" style="display: inline-block;">
                <input type="text" name="query" placeholder="Search my services...">
                <input type="submit" value="Search">
                <button type="button" onclick="window.location.href='view_services.php'" style="margin-left: 10px;">Reset</button>
            </form>
        </div>

    </div>
    <div style="margin-bottom: 20px;">
         <button class="create-button" onclick="window.location.href='create_service.php'">Create New Service</button>
    </div>
    <div class="service-list">
        <?php
        // **Hardcoded array of services with user info**
        $allServices = [
            ['id' => 1, 'user' => 'John Doe', 'name' => 'Sparkle Clean', 'description' => 'House Cleaning', 'price_range' => '$100 - $150', 'views' => 5, 'shortlist' => 2],
            ['id' => 2, 'user' => 'Jane Smith', 'name' => 'Eco Clean', 'description' => 'Deep Cleaning', 'price_range' => '$120 - $180', 'views' => 10, 'shortlist' => 5],
            ['id' => 3, 'user' => 'Peter Jones', 'name' => 'Fresh Start', 'description' => 'Laundry', 'price_range' => '$80 - $120', 'views' => 3, 'shortlist' => 1],
            ['id' => 4, 'user' => 'Mary Brown', 'name' => 'Shine Bright', 'description' => 'House Cleaning', 'price_range' => '$90 - $140', 'views' => 7, 'shortlist' => 3],
            // Add more services here
        ];

        // If there are services in the session (newly created), merge them
        if (isset($_SESSION['services']) && !empty($_SESSION['services'])) {
            //  Make sure to merge in a way that maintains unique IDs.  Don't just use array_merge.
            $sessionServices = $_SESSION['services'];
            $mergedServices = [];
            $existingIds = array_column($allServices, 'id');

            foreach ($allServices as $service) {
                $mergedServices[] = $service;
            }
            foreach ($sessionServices as $service) {
                if (!in_array($service['id'], $existingIds)) {
                    $mergedServices[] = $service;
                }
            }
            $allServices = $mergedServices;
        }

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
                echo "<a href='edit_service.php?id=" . $service['id'] . "'>Edit</a> | ";
                echo "<a href='#' onclick=\"confirmSuspend('" . $service['id'] . "', '" . htmlspecialchars($service['name']) . "')\" class='suspend-button'>Suspend</a>";
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
<script>
function confirmSuspend(serviceId, serviceName) {
    if (confirm("Are you sure you want to suspend the service '" + serviceName + "'?")) {
        window.location.href = 'view_services.php?suspend_id=' + serviceId;
    }
}
</script>
<?php include('includes/footer.php'); ?>