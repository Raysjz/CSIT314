<?php

require_once(__DIR__ . '/db.php');
require_once(__DIR__ . '/loginController.php');
require_once(__DIR__ . '/UserProfile.php');

use PHPUnit\Framework\TestCase;

// ./vendor/bin/phpunit --debug Test/LoginTest.php



class LoginTest extends TestCase
{
    
    public function testLoginSuccess()
    {
       echo "Starting Test Envionrment with User Admin Role Set\n";
       
       
       echo "Running testLoginSuccess: Checking if the user can log in successfully\n";
        $controller = new UserAccountController();
        $result = $controller->authenticate('admin', '1234', 'User Admin');
        $this->assertIsArray($result);
        $this->assertTrue($result['success']);
        $this->assertArrayHasKey('user', $result);
        $this->assertEquals('admin', $result['user']['ua_username']);
        echo "Login success message is correct\n";

    }

    public function testLoginFailure()
    {
        echo "Running testLoginFailure: Checking if the login failure message is displayed correctly\n";
        $controller = new UserAccountController();
        $result = $controller->authenticate('admin', 'wrongpassword', 'User Admin');
        $this->assertIsArray($result);
        $this->assertArrayHasKey('error', $result);
        $this->assertEquals('❌ Incorrect password.', $result['error']);
        echo "Login failure message is correct\n";

    }

    public function testSuspendedUserLogin()
    {
        echo "Testing Suspended Login with Correct Credentials\n";
        $controller = new UserAccountController();
        $result = $controller->authenticate('admin2', '1234', 'User Admin');
        $this->assertIsArray($result);
        $this->assertArrayHasKey('error', $result);
        $this->assertEquals('❌ Your account is suspended. Please contact support.', $result['error']);
        echo "Suspended user error message is correct\n";
    }
}

