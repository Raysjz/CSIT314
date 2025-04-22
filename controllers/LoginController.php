<?php
require_once('db.php');

class LoginController {
    public function authenticate($username, $password, $profile) {
        $conn = Database::getPgConnect(); // Use pg_connect if that’s your choice

        if (empty($username) || empty($profile)) {
            return ['error' => 'Username and profile are required.'];
        }

        $result = pg_query_params($conn, "SELECT * FROM users WHERE username = $1 AND profile = $2", [$username, $profile]);
        $user = pg_fetch_assoc($result);

        if ($user) {
            if ($password === $user['password']) { // 🔐 Or use password_verify() if hashed
                // Return user details
                return [
                    'success' => true,
                    'user' => $user
                ];
            } else {
                return ['error' => '❌ Incorrect password.'];
            }
        } else {
            return ['error' => '❌ No user found with that username/profile.'];
        }
    }
}
?>
