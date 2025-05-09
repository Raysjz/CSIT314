
<div class="Navbar">
    <div class="navbar-left">
        <a href="/CSIT314/boundary/PlatformMgmt/viewSC.php">View Service Categories</a>
        <a href="/CSIT314/boundary/PlatformMgmt/createSC.php">Create Service Categories</a> 
        <a href="/CSIT314/boundary/PlatformMgmt/generateDaily.php">Generate Daily Report</a>
        <a href="/CSIT314/boundary/PlatformMgmt/generateWeekly.php">Generate Weekly Report</a>
        <a href="/CSIT314/boundary/PlatformMgmt/generateMonthly.php">Generate Monthly Report</a> 
    </div>
    <div class="navbar-center">
        <text>Welcome, <?php echo isset($_SESSION['username']) ? $_SESSION['username'] : 'Guest'; ?></text>
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

    .navbar-right text {
    color: white;
    }
    .navbar-center text {
        color: white;
        position: relative;
        left: 50%;
        transform: translateX(-50%);
        font-weight: bold;
        font-style: italic;
    }

</style>
