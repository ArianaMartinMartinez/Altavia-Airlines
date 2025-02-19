<?php

namespace Tests\Feature\Api;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    private function authAdmin() {
        $admin = User::factory()->create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'password' => 'admin',
            'role' => 'admin',
        ]);

        $response = $this->post(route('login'), [
            'email' => $admin->email,
            'password' => 'admin',
        ]);

        return $response->json('access_token');
    }

    public function test_CheckIfCanReceiveAllUsersWithApi() {
        $token = $this->authAdmin();
        User::factory(5)->create();

        $response = $this->get(route('apiHomeUsers'), [
            'token' => $token,
        ]);

        $response->assertStatus(200)
            ->assertJsonCount(6);
    }

    public function test_CheckIfCanReceiveOneUserWithApi() {
        $token = $this->authAdmin();
        $user = $this->post(route('apiStoreUser', [
            'token' => $token,
            'name' => 'Ariana',
            'email' => 'ariana@example.com',
            'password' => 'Ariana1234',
            'role' => 'user',
        ]));
        $data = ['email' => 'ariana@example.com',];

        $response = $this->get(route('apiShowUser', 2), [
            'token' => $token,
        ]);
        $response->assertStatus(200)
            ->assertJsonFragment($data);
    }

    public function test_CheckIfCanCreateNewUserWithApi() {
        $token = $this->authAdmin();
        $user = $this->post(route('apiStoreUser', [
            'token' => $token,
            'name' => 'Ariana',
            'email' => 'ariana@example.com',
            'password' => 'Ariana1234',
        ]));

        $response = $this->get(route('apiHomeUsers'), [
            'token' => $token,
        ]);
        $response->assertStatus(200)
            ->assertJsonCount(2);
    }

    public function test_CheckIfUserCreateReturnErrorIfBadRequestWithApi() {
        $token = $this->authAdmin();
        $response = $this->post(route('apiStoreUser', [
            'token' => $token,
            'email' => 'ariana@example.com',
            'password' => 123456,
        ]));
        $data = ['message' => 'Introduced data is not correct'];

        $response->assertStatus(400)
            ->assertJsonFragment($data);
    }

    public function test_CheckIfCanUpdateOneUserWithApi() {
        $token = $this->authAdmin();
        $response = $this->post(route('apiStoreUser', [
            'token' => $token,
            'name' => 'Ariana',
            'email' => 'ariana@example.com',
            'password' => 'Ariana1234',
        ]));
        $data = ['email' => 'ariana@example.com'];

        $response->assertStatus(201);

        $response = $this->get(route('apiHomeUsers'), [
            'token' => $token,
        ]);
        $response->assertStatus(200)
            ->assertJsonCount(2)
            ->assertJsonFragment($data);

        $response = $this->put(route('apiUpdateUser', 2), [
            'token' => $token,
            'name' => 'Ariana Martín',
            'password' => 'Ariana1234',
        ]);
        $data = ['name' => 'Ariana Martín'];

        $response = $this->get(route('apiHomeUsers'), [
            'token' => $token,
        ]);
        $response->assertStatus(200)
            ->assertJsonCount(2)
            ->assertJsonFragment($data);
    }

    public function test_CheckIfUserUpdateReturnErrorIfBadRequestWithApi() {
        $token = $this->authAdmin();
        $response = $this->post(route('apiStoreUser', [
            'token' => $token,
            'name' => 'Ariana',
            'email' => 'ariana@example.com',
            'password' => 'Ariana1234',
        ]));
        $data = ['email' => 'ariana@example.com'];

        $response->assertStatus(201);

        $response = $this->get(route('apiHomeUsers'), [
            'token' => $token,
        ]);
        $response->assertStatus(200)
            ->assertJsonCount(2)
            ->assertJsonFragment($data);

        $response = $this->put(route('apiUpdateUser', 1), [
            'token' => $token,
            'password' => 1234,
        ]);
        $data = ['message' => 'Introduced data is not correct'];

        $response->assertStatus(400)
            ->assertJsonFragment($data);
    }

    public function test_CheckIfCanDeleteOneUserWithApi() {
        $token = $this->authAdmin();
        User::factory(2)->create();

        $response = $this->get(route('apiHomeUsers'), [
            'token' => $token,
        ]);
        $response->assertStatus(200)
            ->assertJsonCount(3);

        $response = $this->delete(route('apiDestroyUser', 2), [
            'token' => $token,
        ]);

        $response = $this->get(route('apiHomeUsers'), [
            'token' => $token,
        ]);
        $response->assertStatus(200)
            ->assertJsonCount(2);
    }
}
