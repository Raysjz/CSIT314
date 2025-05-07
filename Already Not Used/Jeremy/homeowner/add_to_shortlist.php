<?php
session_start();

include('services_data.php');


// Dummy service data (same as in view_service_details.php)
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

if (isset($_POST['service_id'])) {
    $serviceId = intval($_POST['service_id']);

    if (isset($services[$serviceId])) {
        if (!isset($_SESSION['shortlist'])) {
            $_SESSION['shortlist'] = [];
        }
        if (!array_key_exists($serviceId, $_SESSION['shortlist'])) {
            $_SESSION['shortlist'][$serviceId] = $services[$serviceId];
        }
    }
}

// Redirect back to view_home_owner.php or wherever you want
header("Location: shortlist.php");
exit();
