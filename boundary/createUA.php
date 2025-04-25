<?php
require_once(__DIR__ . '/../boundary/adminNavbar.php');
require_once(__DIR__ . '/../entities/UserAccount.php');
//require_once(__DIR__ . '/../controllers/CreateUAController.php');

class CreateUAC {
    private $userAccount;

    public function __construct(UserAccount $userAccount) {
        $this->userAccount = $userAccount;
    }

    // Handles form submission and delegates to controller
    public function handleFormSubmission($data) {
        // Create the user entity from the form data
        $this->userAccount = new UserAccount(
            null, // ID is auto-generated
            $data['username'],
            $data['password'],
            $data['profile'],
            isset($data['is_suspended']) ? $data['is_suspended'] : false // Default to false if not set
        );

        // Delegate user creation to the controller
        return $this->processUserCreation();
    }

    // Processes the user creation
    private function processUserCreation() {
        $validationResult = $this->userAccount->validateUserAccount(); // Validate the user

        if ($validationResult === "Validation passed.") {
            // If validation is successful, save the user to the database
            return $this->userAccount->saveUser();  
        } else {
            return $validationResult;  // Return validation error message
        }
    }
}


// Main processing logic for user account creation
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collect form data with proper validation for 'profile'
    $data = [
        'username' => $_POST['username'],
        'password' => $_POST['password'],
        'profile' => isset($_POST['profile']) ? $_POST['profile'] : null, // Don't use a default, just set null if not set
        'is_suspended' => isset($_POST['is_suspended']) ? $_POST['is_suspended'] : false // Default to false if not set
    ];

    // Check if the 'profile' field is set and not empty
    if (empty($data['profile'])) {
        echo "❌ Profile is required."; // Provide feedback if the profile is missing
        exit;
    }

    // Instantiate the CreateUserAccountController controller with validated data
    $createUAC = new CreateUAC(new UserAccount(null, $data['username'], $data['password'], $data['profile'], $data['is_suspended']));

    // Call handleFormSubmission method to process and create the user account
    $result = $createUAC->handleFormSubmission($data);

    // Check the result and display an appropriate message
    if ($result === true) {
        //echo "<script>alert('Account successfully created!');</script>"; // You can replace this with a more sophisticated message if needed.
    } else {
       //echo "<script>alert('Error creating account: $result');</script>";
    }
}


?>



<!DOCTYPE html>
<html>
<head>
    <title>Create New Account</title>
    <style>
        body { font-family: Arial; background: #f4f4f4; margin: 0; padding: 40px; }
        .container { background: white; padding: 30px; max-width: 500px; margin: auto; margin-top: 80px; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); }
        h1 { margin-bottom: 20px; }
        label { display: block; margin-top: 15px; }
        input, select { width: 100%; padding: 10px; margin-top: 5px; border-radius: 4px; border: 1px solid #ccc; }
        button { margin-top: 20px; padding: 10px 20px; background: #007bff; border: none; color: white; border-radius: 4px; cursor: pointer; }
        button:hover { background: #0056b3; }
    </style>
</head>
<body>


<style>
    .navbar {
        display: flex;
        justify-content: space-between;
        align-items: center;
        background-color: #343a40;
        padding: 10px 20px;
        position: fixed;
    top: 0;
    left: 0;
    right: 0;
    z-index: 999;
    }

    .navbar a {
        color: white;
        text-decoration: none;
        margin: 0 10px;
        font-weight: bold;
    }

    .navbar a:hover {
        text-decoration: underline;
    }

    .navbar-left, .navbar-right {
        display: flex;
        align-items: center;
    }

    .form-actions {
    display: flex;
    justify-content: space-between;
    margin-top: 30px;
    }

    .form-actions .back-btn {
        background-color: #6c757d;
        color: white;
        padding: 10px 20px;
        text-decoration: none;
        border-radius: 4px;
        font-weight: bold;
    }

    .form-actions .back-btn:hover {
        background-color: #5a6268;
    }

    .form-actions button {
        background-color: #007bff;
        border: none;
        padding: 10px 20px;
        color: white;
        font-weight: bold;
        border-radius: 4px;
        cursor: pointer;
    }

    .form-actions button:hover {
        background-color: #0056b3;
    }

</style>
    <div class="container">
        <h1>Create Account</h1>
        <form id="createForm" action="createUA.php" method="post" onsubmit="return handleFormSubmit(event)">
            
            <label for="username">Username</label>
            <input type="text" id="username" name="username" required>

            <label for="password">Password</label>
            <input type="password" id="password" name="password" required>

            <label for="profile">Profile</label>
            <select id="profile" name="profile" required>
                <option value="">-- Select Profile --</option>
                <option value="User Admin">User Admin</option>
                <option value="Cleaner">Cleaner</option>
                <option value="Home Owner">Home Owner</option>
                <option value="Platform Management">Platform Management</option>
            </select>


            <div class="form-actions">
                <button type="button" class="back-btn" onclick="location.href='/CSIT314/boundary/adminDashboard.php'">Back</button>
                <button type="submit">Create Account</button>
            </div>
        </form>
        <p id="successMessage" style="display:none; color: green; font-weight: bold; margin-top: 20px;">
        ✅ Account successfully created!
        </p>
    </div>
    <script>
    function handleFormSubmit(event) {
        event.preventDefault(); // stop normal form submission

        // Show the message
        document.getElementById('successMessage').style.display = 'block';

        // Optional: submit the form after a short delay (e.g. 1s)
        setTimeout(() => {
            document.getElementById('createForm').submit();
        }, 1000);

        return false;
    }
    </script>