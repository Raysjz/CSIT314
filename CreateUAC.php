<?php
// Include necessary files
require_once 'db.php';         //Ensure this is here to include the Database class
require_once 'UserAccount.php';

class CreateUAC {
    private $userAccount;

    public function __construct(UserAccount $userAccount) {
        $this->userAccount = $userAccount;
    }

    // Process the user creation
    public function processUserCreation() {
        // Validate the user data
        $validationResult = $this->userAccount->validateUA();
        
        if ($validationResult === "Validation passed.") {
            return $this->userAccount->saveUser();  // Save user to the database
        } else {
            return $validationResult;  // Return validation error message
        }
    }

    // Return the validation message (success or failure)
    public function validateMessage($message) {
        return $message;  // Return the validation message
    }
	
	// Handle form submission and call necessary methods
    public function handleFormSubmission($data) {
        // Create and assign the new user account to this controller
        $this->userAccount = new UserAccount(
            null,  // ID is auto-generated
            $data['fullname'],
            $data['username'],
            $data['email'],
            $data['address'],
            $data['password'],
            $data['role']
        );
    
        return $this->processUserCreation();
    }
    
}

// Main processing logic for user account creation (acting as Controller)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collect form data
    $data = [
        'fullname' => $_POST['fullname'],
        'username' => $_POST['username'],
        'email' => $_POST['email'],
        'address' => $_POST['address'],
        'password' => $_POST['password'],
        'role' => $_POST['role'],
    ];

    // Instantiate the CreateUAC controller
    $createUAC = new CreateUAC(new UserAccount(null, $data['fullname'], $data['username'], $data['email'], $data['address'], $data['password'], $data['role']));

    // Call handleFormSubmission method to process and create the user account
    $result = $createUAC->handleFormSubmission($data);

    // Display the result (success or failure message)
    echo $result;
}
?>



<!-- CreateUAPg.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create User Account</title>
</head>
<body>
    <h2>Create User Account</h2>
    <form action="CreateUAC.php" method="POST">
        <label for="fullname">Full Name:</label>
        <input type="text" name="fullname" id="fullname" required><br><br>

        <label for="username">Username:</label>
        <input type="text" name="username" id="username" required><br><br>

        <label for="email">Email:</label>
        <input type="email" name="email" id="email" required><br><br>

        <label for="address">Address:</label>
        <input type="text" name="address" id="address" required><br><br>

        <label for="password">Password:</label>
        <input type="password" name="password" id="password" required><br><br>

        <label for="role">Role:</label>
        <select name="role" id="role">
            <option value="user">User</option>
            <option value="admin">Admin</option>
        </select><br><br>

        <button type="submit">Create Account</button>
    </form>
</body>
</html>
