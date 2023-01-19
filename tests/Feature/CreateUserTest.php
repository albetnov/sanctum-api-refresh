<?php

namespace Tests\Feature;

use App\Helpers\Tester;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class CreateUserTest extends TestCase
{
    use RefreshDatabase;

    public function testCreateUserSuccess()
    {
        $response = $this->postJson(Tester::createApiUrlFrom('register'), [
            'email' => 'newuser@mail.com',
            'username' => 'new_user',
            'password' => 'newuser123',
            'password_confirmation' => 'newuser123',
            'name' => 'new user'
        ]);

        $response
            ->assertOk()
            ->assertJson(fn(AssertableJson $json) => $json
                ->has('message')
                ->where('message', 'User created successfully!')
                ->etc());
    }

    public function testCreateUserFailed()
    {
        $response = $this->postJson(Tester::createApiUrlFrom('register'), [
            'email' => 'not valid',
            'name' => 'new user',
            'password' => 'toofew'
        ]);

        $response->assertUnprocessable()
            ->assertJson(fn(AssertableJson $json) => $json
                ->has('message')
                ->has('errors')
                ->etc()
            );
    }

    public function testDuplicateCreateUser()
    {
        $response = $this->postJson(Tester::createApiUrlFrom('register'), [
            'email' => 'newuser@mail.com',
            'username' => 'new_user',
            'password' => 'newuser123',
            'name' => 'new user',
            'password_confirmation' => 'newuser123'
        ]);

        $response
            ->assertOk()
            ->assertJson(fn(AssertableJson $json) => $json
                ->has('message')
                ->where('message', 'User created successfully!')
                ->etc());

        $response = $this->postJson(Tester::createApiUrlFrom('register'), [
            'email' => 'newuser@mail.com',
            'username' => 'new_user',
            'password' => 'newuser123',
            'name' => 'new user',
            'password_confirmation' => 'newuser123'
        ]);

        $response->assertUnprocessable()
            ->assertJson(fn(AssertableJson $json) => $json
                ->has('message')
                ->has('errors')
                ->etc()
            );
    }
}
