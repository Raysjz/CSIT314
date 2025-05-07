<?php
session_start();  // Start the session to access session variables
if ($_SESSION['profileName'] !== 'Platform Management') {
    header('Location: ../login.php');
    exit();
}
// Include necessary files
require_once(__DIR__ . '/platformNavbar.php');
require_once(__DIR__ . '/../../controllers/UserAccountController.php');



$UAController = new UserAccountController();
$userCount = $UAController->generateUsersCreatedAtNow();


?>

<!DOCTYPE html>
<html>
<head>
    <title>Generate Daily Report Details</title>
    <style>
        .details-container { max-width: 600px; margin: 40px auto; background: #fff; padding: 30px; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); }
        .details-container h2 { margin-top: 0; }
        .details-row { margin-bottom: 18px; }
        .label { font-weight: bold; color: #333; }
        .message {
            padding: 10px;
            margin: 20px 0;
            border-radius: 5px;
            text-align: center;
        }
        .success {
            background-color: #28a745;
            color: white;
        }
        .error {
            background-color: #ffc107;
            color: #856404; 
        }
        .shortlist-button, .remove-button, .back-button {
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            color: white;
            text-decoration: none;
            margin-right: 10px;
            cursor: pointer;
        }
        .shortlist-button { background: #007bff; }
        .shortlist-button:hover { background: #0056b3; }
        .remove-button { background: #dc3545; }
        .remove-button:hover { background: #c82333; }
        .back-button { background: #6c757d; }
        .back-button:hover { background: #5a6268; }
    </style>
</head>
<body>
<div class="details-container">
    <h2>Generate Daily Report Details</h2>
    <div class="details-row"><span class="label">Daily New User Registrations:</span> [Contact information available after booking] </div>
    <?php echo "Number of users created today: " . $userCount; ?>
    <div class="details-row"><span class="label">Price:</span>  [Contact information available after booking]</div>
    <div class="details-row"><span class="label">Availability:</span> [Contact information available after booking]</div>
    <div class="details-row"><span class="label">Description:</span> [Contact information available after booking]</div>
    <div class="details-row"><span class="label">Contact:</span> [Contact information available after booking]</div>
    <div style="margin-top:30px;">
        <a href="viewPC.php" class="back-button">Back to List</a>
    </div>
</div>
</body>
</html>
