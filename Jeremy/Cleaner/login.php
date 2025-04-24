<?php include('includes/header.php'); ?>
<h2>Login</h2>
<form action="login_process.php" method="post">
  <label>Email:</label>
  <input type="email" name="email" required><br>
  <label>Password:</label>
  <input type="password" name="password" required><br>
  <input type="submit" value="Login">
</form>
<?php include('includes/footer.php'); ?>
