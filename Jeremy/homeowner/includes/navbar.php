<!-- navbar.php -->

<div class="navbar">
    <div class="navbar-left">
        <a href="past_bookings.php">Past Bookings</a>
        <a href="view_shortlist.php">Shortlist</a>
    </div>
    <div class="navbar-right">
        <a href="#">Logout</a>
    </div>
</div>

<style>
    
    .navbar {
        display: flex;
        justify-content: space-between;
        align-items: center;
        background-color: #343a40;
        padding: 10px 20px;
        position: relative;
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