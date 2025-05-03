<?php
// Database connection
class Database {
    //Actual DB
    //private static $host = 'aws-0-ap-southeast-1.pooler.supabase.com';
    //private static $port = '5432';
    //private static $dbname = 'postgres';  
    //private static $user = 'postgres.bbmigbyghmmvuhijwuww';
    //private static $password = 'Xx2hraKeUvM4';  

    //TestDB
    private static $host = 'aws-0-ap-southeast-1.pooler.supabase.com';
    private static $port = '5432';  
    private static $dbname = 'postgres';  
    private static $user = 'postgres.askzobvbrkuceqdnndpn'; 
    private static $password = 'Xx2hraKeUvM4';  

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
