<?php

namespace App\Tests\Feature;

use App\Models\User;
use GuzzleHttp;
use Illuminate\Database\Capsule\Manager;
use App\Tests\TestCase;


class RegistrationTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
    }

    public function tearDown(): void
    {
        parent::tearDown();
    }


    /** @test */
    public function createNewAccount()
    {
        $user = [
            'email' => 'user@phpunit.com',
            'password' => 'password_test'
        ];

        $response = $this->http->post('/register', [
            'form_params' => $user
        ]);

        $dbUser = User::where('email', $user['email'])
            ->first();

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertNotNull($dbUser, "User wasn't added to the database.");
        $this->assertTrue(
            password_verify($user['password'], $dbUser->password),
            'Password is not encrypted properly.'
        );

        $dbUser->delete();
    }
}
