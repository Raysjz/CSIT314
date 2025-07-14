<?php
// Connecting via Transaction pooler connection details from Supabase
//$host = 'aws-0-ap-southeast-1.pooler.supabase.com';
//$port = '5432';  // Default PostgreSQL port
//$dbname = 'postgres';  // Default Supabase database
//$user = 'postgres.bbmigbyghmmvuhijwuww';  // Supabase PostgreSQL user
//$password = 'Xx2hraKeUvM4';  // Use your Supabase password

//TestDB
$host = 'aws-0-ap-southeast-1.pooler.supabase.com';
$port = '5432';  // Default PostgreSQL port
$dbname = 'postgres';  // Default Supabase database
$user = 'postgres.askzobvbrkuceqdnndpn';  // Supabase PostgreSQL user
$password = 'Xx2hraKeUvM4';  // Use your Supabase password

// Create a connection string
$conn_str = "pgsql:host=$host;port=$port;dbname=$dbname;user=$user;password=$password";

// Try to connect to the database
try {
    // Create a PDO instance
    $pdo = new PDO($conn_str);

    // Set error mode to exception
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Query to test connection
    $query = 'SELECT version();';  // Get PostgreSQL version

    // Execute the query
    $result = $pdo->query($query);

    // Query to fetch data from user_accounts table
    $query2 = 'SELECT * FROM "user_accounts" LIMIT 10;';  // Fetch up to 10 records from user_accounts
    //$query = 'SELECT * from table user_profiles;';

    // Execute the query
    $result2 = $pdo->query($query2);
    
    // Check if any records are found
    if ($result2->rowCount() > 0) {
        echo "<h2>User Accounts List</h2><ul>";
        
        // Fetch and display the results
        while ($row = $result2->fetch(PDO::FETCH_ASSOC)) {
            echo "<li>ID: " . $row['account_id'] . " | Username: " . $row['ua_username'] . " | Password: " . $row['ua_password'] . "</li>";
        }
        
        echo "</ul>";
    } else {
        echo "No records found in the user_accounts table.";
    }
    
    // Fetch the result and print it
    $row = $result->fetch(PDO::FETCH_ASSOC);
    //echo "Connection successful! PostgreSQL version: " . $row['version'];

    

} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
?>


