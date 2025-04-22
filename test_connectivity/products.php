<?php
$conn = pg_connect("
    host=localhost
    port=5432
    dbname=csit314-database
    user=postgres
    password=1234
");

if (!$conn) {
    die("Connection failed.");
}

// Query products
$result = pg_query($conn, "SELECT * FROM products");

if (!$result) {
    echo "An error occurred.\n";
    exit;
}

// Fetch and display
while ($row = pg_fetch_assoc($result)) {
    echo "<h3>" . htmlspecialchars($row['name']) . "</h3>";
    echo "<p>Price: $" . htmlspecialchars($row['price']) . "</p>";
    echo "<img src='" . htmlspecialchars($row['image']) . "' width='200'><hr>";
}



$result = pg_query_params($conn, "SELECT * FROM users WHERE username = $1 AND profile = $2", array($username, $profile));

        if (!$result) {
            echo "An error occurred.\n";
            exit;
        }

        $user = pg_fetch_assoc($result); // Get one user

        if ($user) {
            echo "<h3>User Found:</h3>";
            echo "Username: " . htmlspecialchars($user['username']) . "<br>";
            echo "Profile: " . htmlspecialchars($user['profile']) . "<br>";
            echo "Is Admin: " . htmlspecialchars($user['isuseradmin']) . "<br>";
            echo "Suspended: " . htmlspecialchars($user['issuspended']) . "<br>";
        }
?>