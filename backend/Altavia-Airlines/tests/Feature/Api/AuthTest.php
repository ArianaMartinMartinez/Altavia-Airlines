<?php

namespace Tests\Feature\Api;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_CheckIfCanRegisterNewUserWithApi() {
        $response = $this->post(route('register'), [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password123',
            'role' => 'user',
        ]);
        $data = ['email' => 'test@example.com'];

        $response->assertStatus(201)
            ->assertJsonFragment($data);
    }

    public function test_CheckIfReturnBadRequestIfBadRegisterWithApi() {
        $response = $this->post(route('register'), [
            'email' => 'test@example.com',
            'password' => 'password123',
            'role' => 'user',
        ]);
        $data = ["{\"name\":[\"The name field is required.\"]}"];

        $response->assertStatus(400)
            ->assertJsonFragment($data);
    }

    public function test_CheckIfCanLoginWithApi() {
        $user = User::factory()->create([
            'name' => 'user',
            'email' => 'user@example.com',
            'password' => 'user',
            'role' => 'user',
        ]);

        $response = $this->post(route('login'), [
            'email' => $user->email,
            'password' => 'user',
        ]);
        $data = ['token_type' => 'bearer'];

        $response->assertStatus(200)
            ->assertJsonFragment($data);
    }

    public function test_CheckIfReturnErrorIfLoginWithWrongCredentialsWithApi() {
        $response = $this->post(route('login'), [
            'email' => 'test@example.com',
            'password' => 'test',
        ]);
        $data = ['error' => 'Email or password not correct'];

        $response->assertStatus(401)
            ->assertJsonFragment($data);
    }

    public function test_CheckIfCanLogoutWithApi() {
        $user = User::factory()->create([
            'name' => 'user',
            'email' => 'user@example.com',
            'password' => 'user',
            'role' => 'user',
        ]);

        $response = $this->post(route('login'), [
            'email' => $user->email,
            'password' => 'user',
        ]);
        $data = ['token_type' => 'bearer'];

        $response->assertStatus(200)
            ->assertJsonFragment($data);
        
        $response = $this->postJson(route('logout'), [
            'token' => $response->json('access_token'),
        ]);
        $data = ['message' => 'Successfully logged out'];
        $response->assertStatus(200)
           ->assertJson($data);
    }

    public function test_CheckIfCanREceiveOwnDataWithApi() {
        $user = User::factory()->create([
            'name' => 'user',
            'email' => 'user@example.com',
            'password' => 'user',
            'role' => 'user',
        ]);

        $response = $this->post(route('login'), [
            'email' => $user->email,
            'password' => 'user',
        ]);
        $data = ['token_type' => 'bearer'];

        $response->assertStatus(200)
            ->assertJsonFragment($data);
        
        $response = $this->post(route('me'), [
            'token' => $response->json('access_token'),
        ]);
        $data = ['name' => 'user'];

        $response->assertStatus(200)
            ->assertJsonFragment($data);
    }

    public function test_CheckIfCanRefreshToken() {
        $user = User::factory()->create([
            'name' => 'user',
            'email' => 'user@example.com',
            'password' => 'user',
            'role' => 'user',
        ]);

        $response = $this->post(route('login'), [
            'email' => $user->email,
            'password' => 'user',
        ]);
        $data = ['token_type' => 'bearer'];

        $response->assertStatus(200)
            ->assertJsonFragment($data);
        
        $response = $this->post(route('refresh'), [
            'token' => $response->json('access_token'),
        ]);
        $response->assertStatus(200)
            ->assertJsonFragment($data);
    }

    public function test_CheckIfReturnErrorIfUserDontHavePermission() {
        $user = User::factory()->create([
            'name' => 'user',
            'email' => 'user@example.com',
            'password' => 'user',
            'role' => 'user',
        ]);

        $response = $this->post(route('login'), [
            'email' => $user->email,
            'password' => 'user',
        ]);
        $data = ['token_type' => 'bearer'];

        $response->assertStatus(200)
            ->assertJsonFragment($data);

        $response = $this->get(route('apiHomeUsers'), [
            'token' => $response->json('access_token'),
        ]);
        $data = ['You do not have permission to access.'];

        $response->assertStatus(200)
            ->assertJsonFragment($data);
    }
}
