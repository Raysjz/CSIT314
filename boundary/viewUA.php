<?php
require_once(__DIR__ . '/../boundary/adminNavbar.php');
require_once(__DIR__ . '/../entities/UserAccount.php');


//------------------------------------------------- ATM ALL BC IN THIS FILE -----------------------------------


// Controller for Viewing User Accounts
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

// Process form submission (for testing user creation)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collect form data
    $data = [
        'username' => $_POST['username'],
        'password' => $_POST['password'],
        'profile' => $_POST['profile'],
        'isSuspended' => isset($_POST['isSuspended']) ? true : false // Default to false if not checked
    ];

    // Instantiate the UserAccount model and controller
    $userAccount = new UserAccount(null, $data['username'], $data['password'], $data['profile'], $data['isSuspended']);
    $viewUAC = new ViewUAC($userAccount);

    // Validate user data
    $validationResult = $userAccount->validateUA();
    if ($validationResult === "Validation passed.") {
        // Save user to the database
        $result = $userAccount->saveUser();
        if ($result) {
            echo "✅ User account has been successfully created.";
        } else {
            echo "❌ Error creating user account.";
        }
    } else {
        echo $validationResult; // Show validation errors
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View User Accounts</title>
    <style>
        body { font-family: Arial; background: #f4f4f4; margin: 0; padding: 40px; }
        .container { background: white; padding: 30px; max-width: 1000px; margin: auto; margin-top: 80px; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); }
        h1 { margin-bottom: 20px; }
        .detail { margin-bottom: 15px; }
        .label { font-weight: bold; }
        .search-container { margin-bottom: 20px; text-align: center; }
        .search-container h2 { margin-top: 0; }
        .search-input { padding: 10px; border: 1px solid #ddd; border-radius: 4px; width: 60%; margin-bottom: 10px; }
        .search-button { padding: 10px 20px; background-color: #007bff; color: white; border: none; border-radius: 4px; cursor: pointer; }
        .search-button:hover { background-color: #0056b3; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 10px; text-align: left; }
        th { background-color: #f2f2f2; font-weight: bold; }
        .actions-buttons button { padding: 8px 12px; margin-right: 5px; border: none; border-radius: 4px; cursor: pointer; }
        .actions-buttons .update-button { background-color: #28a745; color: white; text-decoration: none; }
        .actions-buttons .suspend-button { background-color: #dc3545; color: white; }
        .actions-buttons button:hover { opacity: 0.8; }
        .no-results { text-align: center; font-style: italic; color: #777; }
    </style>
</head>
<body>

    <div class="container">

        <div class="search-container">
            <h2>Search by Username or Id</h2>
            <form action="" method="GET">
                <input type="text" class="search-input" name="search" placeholder="Enter username or user ID">
                <button type="submit" class="search-button">Search</button>
            </form>
        </div>

        <h2>User List</h2>
            <table>
                
                <tbody>
                <?php
                    // Fetch and display user accounts
                    $userAccount = new UserAccount(null, '', '', '', 0); // Empty fields since we just want to fetch users
                    $viewUAC = new ViewUAC($userAccount);
                    $userAccounts = $viewUAC->viewUA();

                    if (empty($userAccounts)) {
                        echo "<p>No user accounts have been created.</p>";
                    } else {
                        echo "<table border='1'>";
                        echo "
                        <tr>
                            <th>User ID</th>
                            <th>Username</th>
                            <th>Profile</th>
                            <th>Is Suspended</th>
                            <th>Actions</th>
                        </tr>";

                        foreach ($userAccounts as $account) {
                            echo "<tr>";
                            echo "<td>" . htmlspecialchars($account->getId()) . "</td>"; // Use the getter for ID
                            echo "<td>" . htmlspecialchars($account->getUsername()) . "</td>"; // Use the getter for username
                            echo "<td>" . htmlspecialchars($account->getProfile()) . "</td>"; // Use the getter for profile
                            echo "<td>" . htmlspecialchars($account->getIsSuspended() ? 'Yes' : 'No') . "</td>"; // Use the getter for isSuspended
                            echo "<td>";
                            echo "<button onclick='window.location.href=\"update_user.php?id=" . $account->getId() . "\"'>Update</button>"; // Use the getter for ID
                            echo "<button onclick='window.location.href=\"suspend_user.php?id=" . $account->getId() . "\"'>Suspend</button>"; // Use the getter for ID
                            echo "</td>";
                            echo "</tr>";
                        }
                        

                        echo "</table>";
                    }
                    ?>
                </tbody>
            </table>
    </div>
</body>