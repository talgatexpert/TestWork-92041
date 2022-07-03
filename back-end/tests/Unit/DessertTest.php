<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Tests\TestCase;

class DessertTest extends TestCase
{
    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function test_get_unauthenticated()
    {
        $response = $this->get('api/desserts');
        $response->assertStatus(401);
    }


    public function test_get_success_with_logged()
    {
        $response = $this->post('api/auth/register', [
            'name' => 'hello',
            'email' => 'hello@gmail.com',
            'password' => '123456'
        ]);
        $result = $response->json();

        $response = $this->get('api/desserts', headers: [
            'Authorization'  => 'Bearer ' . $result['data']['token']
        ]);
        $response->assertJsonStructure([
            'success', 'data' => [], 'total', 'last_page', 'next_page_url', 'prev_page_url'
        ], $response->json());


    }
}
