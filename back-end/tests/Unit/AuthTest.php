<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function test_register()
    {
        $response = $this->post('api/auth/register', [
            'name' => 'hello',
            'email' => 'hello@gmail.com',
            'password' => '123456'
        ]);


        $response->assertJsonStructure([
            'success',
            'data' => [
                'user' => [
                    'id', 'name', 'email', 'created_at',
                ],

                'token'
            ]
        ], $response->json());
    }

    public function test_login()
    {
        $response = $this->post('api/auth/register', [
            'name' => 'hello',
            'email' => 'hello@gmail.com',
            'password' => '123456'
        ]);

        $response = $this->post('api/auth/login', [
            'name' => 'hello',
            'email' => 'hello@gmail.com',
            'password' => '123456'
        ]);


        $response->assertJsonStructure([
            'success',
            'data' => [
                'user' => [
                    'id', 'name', 'email', 'created_at',
                ],

                'token'
            ]
        ], $response->json());
    }

    public function test_get_user()
    {
        $response = $this->post('api/auth/register', [
            'name' => 'hello',
            'email' => 'hello@gmail.com',
            'password' => '123456'
        ]);

        $result = $response->json();

        $response = $this->get('api/auth/user', ['Authorization' => 'Bearer ' . $result['data']['token']]);
        $response->assertJsonStructure([
            'success',
            'data' => [
                'user' => [
                    'id', 'name', 'email', 'created_at',
                ],
            ]
        ], $response->json());
    }

    public function test_register_email_used()
    {
        $this->post('api/auth/register', [
            'name' => 'hello',
            'email' => 'hello@gmail.com',
            'password' => '123456'
        ]);
        $response = $this->post('api/auth/register', [
            'name' => 'hello',
            'email' => 'hello@gmail.com',
            'password' => '123456'
        ]);


        $response->assertJson([
            'message' => "The email has already been taken.",
            'errors' => [
                "email" => ["The email has already been taken."]
            ]
        ]);
    }
}
