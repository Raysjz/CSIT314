<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';
    $profile  = $_POST['profile'] ?? '';

    // Dummy credentials (replace with DB check)
    if ($username === 'admin' && $password === '1234') {
        $_SESSION['username'] = $username;
        $_SESSION['profile'] = $profile;
        header("Location: index.php");
        exit;
    } else {
        $error = "Invalid login!";
    }
}
?>

<!DOCTYPE html>
<html>
<head><title>Login</title></head>
<body>
<h2>Login</h2>
<?php if (isset($error)) echo "<p style='color:red;'>$error</p>"; ?>
<form method="POST" action="login.php">
    Username: <input type="text" name="username" required><br>
    Password: <input type="password" name="password" required><br>
    Profile:
    <select name="profile" required>
        <option value="">-- Select --</option>
        <option value="User Admin">User Admin</option>
        <option value="Home Owner">Home Owner</option>
        <option value="Cleaner">Cleaner</option>
        <option value="Platform Management">Platform Management</option>
    </select><br><br>
    <input type="submit" value="Login">
</form>
</body>
</html>
