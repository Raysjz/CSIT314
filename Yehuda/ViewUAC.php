<?php
// ViewUAC.php (Controller for Viewing User Accounts)

include_once('UserAccount.php'); // Include the Model

class ViewUAC {
    private $userAccount;

    // Constructor to accept the UserAccount model
    public function __construct(UserAccount $userAccount) {
        $this->userAccount = $userAccount;
    }

    // Method to retrieve all user accounts
    public function viewUA() {
        return $this->userAccount->viewUA(); // Calls the UserAccount model's method to get user data
    }
}
?>


<!-- ViewUAPg.html (View for Viewing User Accounts) -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View User Accounts</title>
</head>
<body>
    <h2>User Account Details</h2>

    <?php
    // Include the necessary files
    include_once('ViewUAC.php');  // Controller
    include_once('UserAccount.php');  // Model

    // Instantiate the UserAccount model
    $userAccount = new UserAccount();

    // Instantiate the ViewUAC controller and pass the UserAccount model to it
    $viewUAC = new ViewUAC($userAccount);

    // Fetch all user accounts using the controller
    $userAccounts = $viewUAC->viewUA();

    // Check if there are no user accounts
    if (empty($userAccounts)) {
        echo "<p>No user accounts have been created.</p>";
    } else {
        // Display the user accounts in a table
        echo "<table border='1'>";
        echo "<tr><th>User ID</th><th>Full Name</th><th>Username</th><th>Email</th><th>Role</th><th>Actions</th></tr>";

        foreach ($userAccounts as $account) {
            echo "<tr>";
            echo "<td>" . htmlspecialchars($account->id) . "</td>";
            echo "<td>" . htmlspecialchars($account->fullname) . "</td>";
            echo "<td>" . htmlspecialchars($account->username) . "</td>";
            echo "<td>" . htmlspecialchars($account->email) . "</td>";
            echo "<td>" . htmlspecialchars($account->role) . "</td>";
            echo "<td>";
            // Action buttons (Update and Suspend)
            echo "<button onclick='window.location.href=\"update_user.php?id=" . $account->id . "\"'>Update</button>";
            echo "<button onclick='window.location.href=\"suspend_user.php?id=" . $account->id . "\"'>Suspend</button>";
            echo "</td>";
            echo "</tr>";
        }

        echo "</table>";
    }
    ?>
</body>
</html>