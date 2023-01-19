<?php

namespace Tests\Feature;

use App\Helpers\Tester;
use App\Helpers\Tokens;
use Database\Seeders\TokenSeeder;
use Database\Seeders\UserSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class TokenTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(UserSeeder::class);
        $this->seed(TokenSeeder::class);
    }

    public function testTokenSuccess()
    {
        $response = $this
            ->withHeader('Accept', 'application/json')
            ->withToken(Tester::createTokenFrom(Tokens::FIRST_TOKEN))
            ->get(Tester::createApiUrlFrom('user'));

        $response->assertOk()->assertJson(fn(AssertableJson $json) => $json
            ->has('user')
            ->etc());
    }

    public function testTokenInvalid()
    {
        $response = $this
            ->withHeader('Accept', 'application/json')
            ->withToken('jfijwifw')
            ->get(Tester::createApiUrlFrom('user'));

        $response->assertUnauthorized();
    }

    public function testTokenExpired()
    {
        $response = $this
            ->withHeader('Accept', 'application/json')
            ->withToken(Tester::createTokenFrom(Tokens::SECOND_TOKEN))
            ->get(Tester::createApiUrlFrom("user"));

        $response->assertUnauthorized();
    }

    public function testRefreshTokenWork()
    {
        $response = $this
            ->postJson(Tester::createApiUrlFrom('refresh'), [
                'refresh_token' => Tester::accessRefreshTokenOf(1)
            ]);

        $response->assertOk()
            ->assertJson(fn(AssertableJson $json) => $json
                ->hasAll(['token', 'expires_in', 'refresh_token', 'refresh_token_expires_in'])
                ->etc());

        $response = $this
            ->withToken($response->json()['token'])
            ->withHeader('Accept', 'application/json')
            ->get(Tester::createApiUrlFrom('user'));

        $response->assertOk()->assertJson(fn(AssertableJson $json) => $json
            ->has('user')
            ->etc());
    }
}
