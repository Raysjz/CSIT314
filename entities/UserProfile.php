<?php

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

class UserAccount {
    protected $id;
    protected $username;
    protected $password;
    protected $profile;
    protected $isSuspended;

    public function __construct($id, $username, $password, $profile, $isSuspended) {
        $this->id = $id;
        $this->username = $username;
        $this->password = $password;
        $this->profile = $profile;
        $this->isSuspended = $isSuspended;
    }

    // Getter methods
    public function getId() {
        return $this->id;
    }

    public function getUsername() {
        return $this->username;
    }

    public function getPassword() {
        return $this->password;
    }

    public function getProfile() {
        return $this->profile;
    }

    public function getIsSuspended() {
        return $this->isSuspended;
    }

    // Validate user input data
    public function validateUserAccount() {
        if (empty($this->username) || empty($this->password) || empty($this->profile)) {
            return "All fields are required.";
        }

        $db = Database::getPDO();

        // Check if username already exists
        $stmt = $db->prepare("SELECT * FROM user_accounts WHERE username = :username");
        $stmt->bindParam(':username', $this->username);
        $stmt->execute();
        if ($stmt->rowCount() > 0) {
            return "Username already taken.";
        }

        return "Validation passed.";
    }

    // Save user to the database
    public function saveUser() {
        $db = Database::getPDO();

        $stmt = $db->prepare("INSERT INTO user_accounts (username, password, profile, is_suspended) 
                              VALUES (:username, :password, :profile, :is_suspended)");

        $stmt->bindParam(':username', $this->username);
        $stmt->bindParam(':password', $this->password);
        $stmt->bindParam(':profile', $this->profile);
        $stmt->bindParam(':is_suspended', $this->isSuspended, PDO::PARAM_BOOL);

        return $stmt->execute();
    }

    // View all user accounts (ordered by ID ascending)
    public static function viewUserAccounts() {
        $db = Database::getPDO();

        $stmt = $db->prepare("SELECT * FROM user_accounts ORDER BY id ASC");
        $stmt->execute();

        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $userAccounts = [];
        foreach ($result as $row) {
            $userAccounts[] = new UserAccount(
                $row['id'],
                $row['username'],
                $row['password'],
                $row['profile'],
                isset($row['is_suspended']) ? (bool)$row['is_suspended'] : false
            );
        }

        return $userAccounts;
    }

    // Search user accounts by username or id
    public static function searchUserAccounts($searchQuery) {
        $db = Database::getPDO();

        if (is_numeric($searchQuery)) {
            $stmt = $db->prepare("SELECT * FROM user_accounts WHERE id = :search");
        } else {
            $stmt = $db->prepare("SELECT * FROM user_accounts WHERE username LIKE :search");
            $searchQuery = "%" . $searchQuery . "%";
        }

        $stmt->bindParam(':search', $searchQuery);
        $stmt->execute();

        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $userAccounts = [];
        foreach ($result as $row) {
            $userAccounts[] = new UserAccount(
                $row['id'],
                $row['username'],
                $row['password'],
                $row['profile'],
                isset($row['is_suspended']) ? (bool)$row['is_suspended'] : false
            );
        }

        return $userAccounts;
    }

    // Update user account by id
    public function updateUserAccount() {
        $db = Database::getPDO();

        $stmt = $db->prepare("UPDATE user_accounts 
            SET username = :username, password = :password, profile = :profile, is_suspended = :is_suspended 
            WHERE id = :id");

        $stmt->bindParam(':id', $this->id);
        $stmt->bindParam(':username', $this->username);
        $stmt->bindParam(':password', $this->password);
        $stmt->bindParam(':profile', $this->profile);
        $stmt->bindParam(':is_suspended', $this->isSuspended, PDO::PARAM_BOOL);

        return $stmt->execute();
    }

    // Static: Fetch user by ID
    public static function getUserById($id) {
        $db = Database::getPDO();

        $stmt = $db->prepare("SELECT * FROM user_accounts WHERE id = :id");
        $stmt->bindParam(':id', $id);
        $stmt->execute();

        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            return new UserAccount(
                $user['id'],
                $user['username'],
                $user['password'],
                $user['profile'],
                isset($user['is_suspended']) ? (bool)$user['is_suspended'] : false
            );
        } else {
            return null;
        }
    }

    // Suspend a user (set is_suspended to true)
    public function suspendUserAccount() {
        $db = Database::getPDO();

        $stmt = $db->prepare("UPDATE user_accounts SET is_suspended = true WHERE id = :id");
        $stmt->bindParam(':id', $this->id);

        return $stmt->execute();
    }
}
