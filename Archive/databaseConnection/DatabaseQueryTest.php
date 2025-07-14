<?php

require_once(__DIR__ . '/db.php');
use PHPUnit\Framework\TestCase;

class DatabaseQueryTest extends TestCase
{
    public function testCanFetchUserAccounts()
    {
        $conn = Database::getPgConnect();

        // Define test values
        $username = 'admin';
        $profile = 'User Admin';

        // Query for a specific user
        $result = pg_query_params(
            $conn,
            "SELECT * FROM user_accounts WHERE ua_username = $1 AND profile_name = $2",
            [$username, $profile]
        );

        $user = pg_fetch_assoc($result);

        // Print the row for debugging
        print_r($user);

        // Assert: Did we get a result?
        $this->assertIsArray($user, "Result should be an array (user found)");
        // Optionally, check specific values
        $this->assertEquals($username, $user['ua_username']);
        $this->assertEquals($profile, $user['profile_name']);
    }
}
