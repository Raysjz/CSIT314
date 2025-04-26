<?php
session_start();  // Start the session to access session variables
?>

<div class="Navbar">
    <div class="navbar-left">
        <a href="">View Service</a>
        <a href="">Create Service</a> 
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
