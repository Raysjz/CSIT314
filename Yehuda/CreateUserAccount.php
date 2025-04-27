<?php
require_once('db.php'); // Ensure this is here to include the Database class

class UserAccount {
        protected $id;
        protected $fullname;
        protected $username;
        protected $email;
        protected $address;
        protected $password;
        protected $role;

    public function __construct($id, $fullname, $username, $email, $address, $password, $role) {
        $this->id = $id;
        $this->fullname = $fullname;
        $this->username = $username;
        $this->email = $email;
        $this->address = $address;
        $this->password = $password;
        $this->role = $role;
    }

    // Validate user input data
    public function validateUA() {
        if (empty($this->fullname) || empty($this->username) || empty($this->email) || empty($this->password)) {
            return "All fields are required.";
        }

        $db = Database::getPDO(); // ✅ correct method

        // Check if email already exists
        $stmt = $db->prepare("SELECT * FROM useraccount WHERE email = :email");
        $stmt->bindParam(':email', $this->email);
        $stmt->execute();
        if ($stmt->rowCount() > 0) {
            return "Email already exists.";
        }

        // Check if username already exists
        $stmt = $db->prepare("SELECT * FROM useraccount WHERE username = :username");
        $stmt->bindParam(':username', $this->username);
        $stmt->execute();
        if ($stmt->rowCount() > 0) {
            return "Username already taken.";
        }

        return "Validation passed.";
    }

    // Save user to the database
    public function saveUser() {
        $db = Database::getPDO(); // 

        $hashedPassword = password_hash($this->password, PASSWORD_DEFAULT);  // Hash the password

        $stmt = $db->prepare("INSERT INTO useraccount (fullname, username, email, address, password, role) 
                              VALUES (:fullname, :username, :email, :address, :password, :role)");
        $stmt->bindParam(':fullname', $this->fullname);
        $stmt->bindParam(':username', $this->username);
        $stmt->bindParam(':email', $this->email);
        $stmt->bindParam(':address', $this->address);
        $stmt->bindParam(':password', $hashedPassword);
        $stmt->bindParam(':role', $this->role);

        return $stmt->execute() ? "✅ User account have been successfully created." : "❌ Error creating user account.";
    }
}
?>
