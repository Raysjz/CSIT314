<?php
require_once(__DIR__ . '/db.php');
use PHPUnit\Framework\TestCase;

class LoginTest extends TestCase
{
    public function testLoginSuccess()
    {
        echo "Testing successful login...\n";
        $conn = Database::getPgConnect();
        $username = 'admin';
        $password = '1234';
        $profile = 'User Admin';

        $result = pg_query_params(
            $conn,
            'SELECT * FROM user_accounts WHERE ua_username = $1 AND ua_password = $2 AND profile_name = $3',
            [$username, $password, $profile]
        );
        $user = pg_fetch_assoc($result);

        $this->assertIsArray($user);
        $this->assertEquals($username, $user['ua_username']);
        echo "Login success message is correct\n";

        //print_r($user); // For debugging
    }

    public function testLoginFailure()
    {
        echo "Testing failed login (wrong password)...\n";
        $conn = Database::getPgConnect();
        $username = 'admin';
        $password = '2244';
        $profile = 'User Admin';

        $result = pg_query_params(
            $conn,
            'SELECT * FROM user_accounts WHERE ua_username = $1 AND ua_password = $2 AND profile_name = $3',
            [$username, $password, $profile]
        );
        $user = pg_fetch_assoc($result);

        $this->assertFalse($user); // Should be false if login fails
        echo "Login failure message is correct\n";

        //print_r($user); // For debugging
    }

    public function testSuspendedUserLogin()
    {
        echo "Testing login for suspended user...\n";
        $conn = Database::getPgConnect();
        $username = 'admin2';
        $password = '1234';
        $profile = 'User Admin';

        $result = pg_query_params(
            $conn,
            'SELECT * FROM user_accounts WHERE ua_username = $1 AND ua_password = $2 AND profile_name = $3',
            [$username, $password, $profile]
        );
        $user = pg_fetch_assoc($result);

        $this->assertIsArray($user); // Should find the user, but is suspended
        $this->assertTrue($user['is_suspended'] === true || $user['is_suspended'] === 't'); // Asserts User is Suspended
        echo "Suspended user error message is correct\n";

        //print_r($user); // For debugging

    }

}
