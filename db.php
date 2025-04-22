<?php
// Database connection
$conn = pg_connect("
    host=localhost
    port=5432
    dbname=csit314-database
    user=postgres
    password=1234
");

// Check connection status
if (!$conn) {
    die("❌ Database connection failed.");
} else {
    //echo "✅ Connected to PostgreSQL successfully.<br><br>";
}

class Database {
    private static $host = 'localhost';
    private static $port = '5432';
    private static $dbname = 'csit314-database';
    private static $user = 'postgres';
    private static $password = '1234';

    // 1)PDO connection (OOP-friendly)
    public static function getPDO() {
        $dsn = "pgsql:host=" . self::$host . ";port=" . self::$port . ";dbname=" . self::$dbname;

        try {
            $conn = new PDO($dsn, self::$user, self::$password);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $conn;
        } catch (PDOException $e) {
            die("❌ PDO Connection failed: " . $e->getMessage());
        }
    }

    // 2)pg_connect connection (procedural)
    public static function getPgConnect() {
        $connStr = "host=" . self::$host .
                   " port=" . self::$port .
                   " dbname=" . self::$dbname .
                   " user=" . self::$user .
                   " password=" . self::$password;

        $conn = pg_connect($connStr);
        if (!$conn) {
            die("❌ pg_connect failed.");
        }
        return $conn;
    }
}

?>
