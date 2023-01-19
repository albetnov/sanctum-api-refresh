<?php

namespace Tests\Feature;

use App\Helpers\Tester;
use Database\Seeders\TokenSeeder;
use Database\Seeders\UserSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class LoginTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(UserSeeder::class);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testLoginSuccess()
    {
        $response = $this->postJson(Tester::createApiUrlFrom('login'), [
            'email' => 'admin@mail.com',
            'password' => 'admin12345',
        ]);

        $response
            ->assertOk()
            ->assertJson(fn(AssertableJson $json) => $json
                ->hasAll(['message', 'token', 'expires_in', 'refresh_token', 'refresh_token_expires_in'])
                ->etc());
    }

    public function testLoginFailedValidation()
    {
        $response = $this->postJson(Tester::createApiUrlFrom('login'), [
            'email' => 'notvalid'
        ]);

        $response->assertUnprocessable()->assertJson(fn(AssertableJson $json) => $json
            ->hasAll(['message', 'errors'])
            ->etc());
    }
}
