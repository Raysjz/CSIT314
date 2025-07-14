<?php
session_start(); // Start the session

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate the form data (important for security and data integrity)
    $serviceName = trim($_POST['service_name']);
    $serviceType = trim($_POST['type']);
    $priceRange = trim($_POST['price_range']);
    $availability = trim($_POST['availability']);
    $contactInfo = trim($_POST['contact_info']);

    // **Very important:** Sanitize and validate ALL user input!
    // The following is a basic example.  You should use more robust validation.
    if (empty($serviceName) || empty($serviceType) || empty($priceRange) || empty($availability) || empty($contactInfo)) {
        $_SESSION['error_message'] = "All fields are required.";
        header("Location: create_service.php"); // Redirect back to the form
        exit();
    }

    //  Example of more robust validation (using filter_var):
    // if (!filter_var($contactInfo, FILTER_VALIDATE_EMAIL)) {
    //     $_SESSION['error_message'] = "Invalid contact information.";
    //     header("Location: create_service.php");
    //     exit();
    // }


    // Create an array to hold the new service data
    $newService = array(
        'id' => uniqid(), //  Generate a unique ID.  For numeric, you'd need to track the last ID.
        'user' => 'Current User', //  You'd replace this with the actual logged-in user's name or ID.  Get this from your authentication system.
        'name' => $serviceName,
        'description' => $serviceType, //  Consider having separate fields for type and description in your form.
        'price_range' => $priceRange,
        'availability' => $availability,
        'contact_info' => $contactInfo, // Storing contact info
    );

    // Store the new service in the session.
    //  Check if the 'services' session variable exists. If not, initialize it as an empty array.
    if (!isset($_SESSION['services'])) {
        $_SESSION['services'] = array();
    }
    $_SESSION['services'][] = $newService; // Add to the end of the array.

    // Set a success message
    $_SESSION['success_message'] = "Service '" . htmlspecialchars($serviceName) . "' created successfully!";

    // Redirect back to the view_services.php page
    header("Location: view_services.php");
    exit(); //  Always use exit() after a header redirect.
} else {
    // If the form wasn't submitted via POST, redirect to the form.
    header("Location: create_service.php");
    exit();
}
?>
