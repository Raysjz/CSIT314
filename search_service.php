<?php require_once(__DIR__ . '/cleanerNavbar.php'); ?>

<h2>Search Results</h2>

<?php
// 1. Connect to your database
$servername = "your_servername"; // Replace with your database server name
$username = "your_username";     // Replace with your database username
$password = "your_password";     // Replace with your database password
$dbname = "your_database";       // Replace with your database name

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// 2. Retrieve the search query
if (isset($_GET['query'])) {
    $searchQuery = mysqli_real_escape_string($conn, $_GET['query']); // Sanitize input

    // 3. Construct the SQL query (adjust based on your database schema)
    $sql = "SELECT * FROM services WHERE name LIKE '%$searchQuery%' OR description LIKE '%$searchQuery%'";
    $result = $conn->query($sql);

    // 4. Display the search results
    if ($result->num_rows > 0) {
        echo "<div class='service-list'>";
        while ($row = $result->fetch_assoc()) {
            echo "<div class='service-card'>";
            echo "<h3>" . htmlspecialchars($row['name']) . "</h3>";
            echo "<p>Description: " . htmlspecialchars($row['description']) . "</p>";
            echo "<p>Price: $" . htmlspecialchars($row['price']) . "</p>"; // Adjust field name
            echo "<p>Availability: " . htmlspecialchars($row['availability']) . "</p>"; // Adjust field name
            echo "<a href='edit_service.php?id=" . $row['id'] . "'>Edit</a> | ";
            echo "<a href='suspend_service.php?id=" . $row['id'] . "'>Suspend</a>";
            echo "</div>";
        }
        echo "</div>";
    } else {
        echo "<p>No services found matching your search query.</p>";
    }
} else {
    echo "<p>Please enter a search query.</p>";
}

// 5. Close the database connection
$conn->close();
?>
