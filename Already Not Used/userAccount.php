<?php
// Database Connection
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


require_once('db.php'); // Ensure this is here to include the Database class

class UserAccount {
    protected $id;
    protected $username;
    protected $password;
    protected $profile;

    public function __construct($id, $username, $password, $profile) {
        $this->id = $id;
        $this->username = $username;
        $this->password = $password;
        $this->profile = $profile;
    }

    // Validate user input data
    public function validateUA() {
        if (empty($this->username) || empty($this->password)) {
            return "All fields are required.";
        }

        $db = Database::getPDO(); 

        // Check if username already exists
        $stmt = $db->prepare("SELECT * FROM useraccount WHERE username = :username");
        $stmt->bindParam(':username', $this->username);
        $stmt->execute();
        if ($stmt->rowCount() > 0) {
            return "Username already taken.";
        }
        $stmt->execute();
        if ($stmt->rowCount() > 0) {
            return "Username already taken.";
        }

        return "Validation passed.";
    }

    // Save user to the database
    public function saveUser() {
        $db = Database::getPDO(); 

        $stmt = $db->prepare("INSERT INTO useraccount (username, password, profile) 
                              VALUES (:username,:password, :profile)");
        $stmt->bindParam(':username', $this->username);
        $stmt->bindParam(':password', $this->password);
        $stmt->bindParam(':role', $this->profile);

        return $stmt->execute() ? "✅ User account have been successfully created." : "❌ Error creating user account.";
    }
	
	// Method to view all user accounts
    public function viewUA() {
        $db = Database::getPDO();   // Reuse the existing database connection from db.php
        $stmt = $db->prepare("SELECT * FROM users");
        $stmt->execute();
        
        // Fetch all user accounts as an array of UserAccount objects
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Map the results to UserAccount objects
        $userAccounts = [];
        foreach ($result as $row) {
            $userAccounts[] = new UserAccount(
                $row['id'],
                $row['username'],
                $row['password'],
                $row['profile']
            );
        }
        
        return $userAccounts;
    }
}
?>
