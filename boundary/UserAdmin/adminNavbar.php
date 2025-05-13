<div class="Navbar">
    <div class="navbar-left">
        <a href="/CSIT314/boundary/UserAdmin/viewUA.php">View Accounts</a>
        <a href="/CSIT314/boundary/UserAdmin/createUA.php">Create Account</a> 
        <a href="/CSIT314/boundary/UserAdmin/viewUP.php">View Profiles</a>
        <a href="/CSIT314/boundary/UserAdmin/createUP.php">Create Profile</a> 
    </div>
    
    <div class="navbar-center">
        <text>Welcome, <?php echo isset($_SESSION['username']) ? $_SESSION['username'] : 'Guest'; ?></text>
    </div>
    
    <div class="navbar-right">
        <a href="/CSIT314/boundary/logout.php">Logout</a>
    </div>
</div>

<!-- User Admin Nav Bar -->
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

    .navbar-left, .navbar-right, .navbar-center {
        display: flex;
        align-items: center;
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

    .navbar-center text {
        color: white;
        position: absolute;
        left: 50%;
        transform: translateX(-50%);
        font-weight: bold;
        font-style: italic;
    }
</style>

