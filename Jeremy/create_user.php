
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
<div class="Navbar">
    <div class="navbar-left">
        <a href="/CSIT314/boundary/viewUA.php">View Accounts</a>
        <a href="/CSIT314/boundary/createUA.php">Create Account</a> 
        <a href="/CSIT314/boundary/viewUP.php">View Profiles</a>
        <a href="/CSIT314/boundary/createUP.php">Create Profile</a> 
    </div>
    <div class="navbar-right">
        <text color ="white" >Welcome, <?php echo isset($_SESSION['username']) ? $_SESSION['username'] : 'Guest'; ?> </text>
        <a href="/CSIT314/logout.php">Logout</a>
    </div>
</div>

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

    .navbar-right text {
    color: white;
    }

</style>

<style>
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
        <form id="createForm" action="create_account_process.php" method="post" onsubmit="return handleFormSubmit(event)">
            
            <label for="fullname">Full Name</label>
            <input type="text" id="fullname" name="fullname" required>

            <label for="username">Username</label>
            <input type="text" id="username" name="username" required>

            <label for="email">Email</label>
            <input type="email" id="email" name="email" required>

            <label for="password">Password</label>
            <input type="password" id="password" name="password" required>

            <label for="role">Role</label>
            <select id="role" name="role" required>
                <option value="User Admin">User Admin</option>
                <option value="Cleaner">Cleaner</option>
                <option value="Home Owner">Home Owner</option>
                <option value="Platform Management">Platform Management</option>
            </select>

            <div class="form-actions">
                <button type="button" class="back-btn" onclick="location.href='index.php'">Back</button>
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
</body>
</html>
