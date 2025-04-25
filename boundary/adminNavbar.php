<!-- adminNavbar.php -->
<div class="Navbar">
    <div class="navbar-left">
        <a href="../boundary/viewUA.php">View Account</a>
        <a href="../boundary/viewUP.php">View Profile</a>
        <a href="../boundary/createUA.php">Create Account</a> 
        <a href="../boundary/createUP.php">Create Profile</a> 
    </div>
    <div class="navbar-right">
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
</style>
