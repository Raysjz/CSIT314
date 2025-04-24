<?php
// Include necessary files
require_once(__DIR__ . '/entities/userAccount.php');
require_once(__DIR__ . '/boundary/adminNavbar.php');

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
            $data['username'],
            $data['password'],
            $data['profile'],
            $data['isSuspended'] ?? false // Default to false if not set
        );
    
        return $this->processUserCreation();
    }
}

// Main processing logic for user account creation (acting as Controller)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collect form data
    $data = [
        'username' => $_POST['username'],
        'password' => $_POST['password'],
        'profile' => $_POST['profile'],
        'isSuspended' => isset($_POST['isSuspended']) ? $_POST['isSuspended'] : false
    ];

    // Instantiate the CreateUAC controller
    $createUAC = new CreateUAC(new UserAccount(null, $data['username'], $data['password'], $data['profile'], $data['isSuspended']));

    // Call handleFormSubmission method to process and create the user account
    $result = $createUAC->handleFormSubmission($data);

    // Display the result (success or failure message)
    echo $result;
}
?>


<!DOCTYPE html>
<html>
<head>
    <title>Create New Account</title>
    <div class="container">
        <h1>Create Account</h1>
        <form id="createForm" action="CreateUA.php" method="post" onsubmit="return handleFormSubmit(event)">
            
            <label for="username">Username</label>
            <input type="text" id="username" name="username" required>

            <label for="password">Password</label>
            <input type="password" id="password" name="password" required>

            <label for="profile">Profile</label>
            <select id="profile" name="profile" required>
                <option value="User Admin">User Admin</option>
                <option value="Cleaner">Cleaner</option>
                <option value="Home Owner">Home Owner</option>
                <option value="Platform Management">Platform Management</option>
            </select>

            <label for="isSuspended">Suspended</label>
            <input type="checkbox" id="isSuspended" name="isSuspended" value="true">

            <div class="form-actions">
                <button type="button" class="back-btn" onclick="location.href='adminDashboard.php'">Back</button>
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

        // Show the success message
        document.getElementById('successMessage').style.display = 'block';

        // Optional: submit the form after a short delay (e.g. 1s)
        setTimeout(() => {
            document.getElementById('createForm').submit();
        }, 1000);

        return false;
    }
    </script>
</body>
</html>
