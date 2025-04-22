<?php
$host = "ep-tight-wave-a1qu4q86-pooler.ap-southeast-1..neon.tech";
$db   = "neondb";
$user = "neondb_owner";
$pass = "npg_jbZC5Yyxcr3R";
$port = "5432"; // default
$sslmode = "require";

// postgresql://neondb_owner:npg_jbZC5Yyxcr3R@ep-quiet-sunset-a129nqz2-pooler.ap-southeast-1.aws.neon.tech/neondb?sslmode=require
try {
    $dsn = "pgsql:host=$host;port=$port;dbname=$db;sslmode=$sslmode";
    $pdo = new PDO($dsn, $user, $pass);

    // Optional: throw exceptions for errors
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // echo "Connected to Neon!";
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
    exit;
}
?>