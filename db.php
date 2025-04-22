<?php
$host = "ep-tight-wave-a1qu4q86-pooler.ap-southeast-1.aws.neon.tech";
$db   = "neondb";
$user = "neondb_owner";
$pass = "npg_jbZC5Yyxcr3R";
$port = "5432";
$sslmode = "require";
$endpoint_id = "ep-tight-wave-a1qu4q86";

// URL-encoded: options=--endpoint=ep-tight-wave-a1qu4q86 → options=%2D%2Dendpoint%3Dep-tight-wave-a1qu4q86
$dsn = "pgsql:host=$host;port=$port;dbname=$db;sslmode=$sslmode;options=%2D%2Dendpoint%3D$endpoint_id";

try {
    $pdo = new PDO($dsn, $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "✅ Connected to Neon!";
} catch (PDOException $e) {
    echo "❌ Connection failed: " . $e->getMessage();
    exit;
}
?>
