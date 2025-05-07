<?php
session_start();

// Dummy review submission handling (you can save the review to a database or session)
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Fetch the review
    $review = isset($_POST['review']) ? trim($_POST['review']) : '';

    // Here you can save the review to a database, for example
    // For now, we'll just simulate a successful submission

    // Assuming the review is valid, redirect to the "Thank You" page
    if (!empty($review)) {
        // Store the review in a session or database here

        // Redirect to thank you page
        header('Location: review_thank_you.php');
        exit();  // Make sure to exit after redirect
    } else {
        // If review is empty, stay on the form page and show an error
        echo "Please write a review before submitting.";
    }
}
?>
