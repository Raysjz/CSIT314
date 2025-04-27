<?php
$conn = pg_connect("
    host=localhost
    port=5432
    dbname=csit314-database
    user=postgres
    password=1234
");

if ($conn) {
    echo "✅ Connection successful!";
} else {
    echo "❌ Connection failed.";
}

?>



