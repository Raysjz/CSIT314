<?php
//require_once(__DIR__ . '/../entities/loginEntity.php');

class LoginController {
    public function authenticate($username, $password, $profile) {
        // Instantiate the LoginEntity to handle the actual authentication logic
        $loginEntity = new LoginEntity();  // Correct class name
        return $loginEntity->validateUser($username, $password, $profile);  // Correct method call
    }
}

?>
