<?php
// Connecting via Transaction pooler connection details from Supabase
$host = '';
$port = '5432';  // Default PostgreSQL port
$dbname = 'postgres';  // Default Supabase database
$user = '';  // Supabase PostgreSQL user
$password = '';

// Create a connection string
$conn_str = "";

// Try to connect to the database
try {
    // Create a PDO instance
    $pdo = new PDO($conn_str);

    // Set error mode to exception
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "✅Connected to the Supabase database via transaction pooler successfully!";
} catch (PDOException $e) {
    echo "❌Connection failed: " . $e->getMessage();
}
?>
