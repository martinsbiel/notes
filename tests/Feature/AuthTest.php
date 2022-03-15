<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_login(){
        $user = User::factory()->create();
        
        $data = [
            'email' => $user->email,
            'password' => 'secret'
        ];

        $response = $this->postJson('/api/login', $data);

        $response->assertStatus(200);
        $response->assertJsonStructure(['token']);
    }

    public function test_user_can_logout(){
        $user = User::factory()->create();

        $response = $this->actingAs($user)->postJson('/api/v1/logout');
        $response->assertStatus(200);
        $response->assertJsonStructure(['msg']);
    }

    public function test_user_can_refresh_token(){
        $user = User::factory()->create();

        $response = $this->actingAs($user)->postJson('/api/refresh');
        $response->assertStatus(200);
        $response->assertJsonStructure(['token']);
    }

    public function test_user_can_register(){
        $data = [
            'name' => 'Gabriel Martins',
            'email' => 'gabriel@email.com',
            'password' => 'secret'
        ];

        $response = $this->postJson('/api/register', $data);
        $response->assertStatus(201);
        $response->assertJsonStructure(['name', 'email', 'updated_at', 'created_at', 'id']);
    }
}
