<?php

namespace Tests\Feature\Api;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    public function test_CheckIfCanReceiveAllUsersWithApi() {
        User::factory(5)->create();

        $response = $this->get(route('apiHomeUsers'));

        $response->assertStatus(200)
            ->assertJsonCount(5);
    }

    public function test_CheckIfCanReceiveOneUserWithApi() {
        $user = $this->post(route('apiStoreUser', [
            'name' => 'Ariana',
            'email' => 'ariana@example.com',
            'password' => 'Ariana1234',
            'role' => 'user',
        ]));
        $data = ['email' => 'ariana@example.com',];

        $response = $this->get(route('apiShowUser', 1));
        $response->assertStatus(200)
            ->assertJsonFragment($data);
    }

    public function test_CheckIfCanCreateNewUserWithApi() {
        $user = $this->post(route('apiStoreUser', [
            'name' => 'Ariana',
            'email' => 'ariana@example.com',
            'password' => 'Ariana1234',
        ]));

        $response = $this->get(route('apiHomeUsers'));
        $response->assertStatus(200)
            ->assertJsonCount(1);
    }

    public function test_CheckIfUserCreateReturnErrorIfBadRequestWithApi() {
        $response = $this->post(route('apiStoreUser', [
            'email' => 'ariana@example.com',
            'password' => 123456,
        ]));
        $data = ['message' => 'Introduced data is not correct'];

        $response->assertStatus(400)
            ->assertJsonFragment($data);
    }

    public function test_CheckIfCanUpdateOneUserWithApi() {
        $response = $this->post(route('apiStoreUser', [
            'name' => 'Ariana',
            'email' => 'ariana@example.com',
            'password' => 'Ariana1234',
        ]));
        $data = ['email' => 'ariana@example.com'];

        $response->assertStatus(201);

        $response = $this->get(route('apiHomeUsers'));
        $response->assertStatus(200)
            ->assertJsonCount(1)
            ->assertJsonFragment($data);

        $response = $this->put(route('apiUpdateUser', 1), [
            'name' => 'Ariana Martín',
            'email' => 'ariana.martin@example.com',
            'password' => 'Ariana1234',
        ]);
        $data = ['email' => 'ariana.martin@example.com'];

        $response = $this->get(route('apiHomeUsers'));
        $response->assertStatus(200)
            ->assertJsonCount(1)
            ->assertJsonFragment($data);
    }

    public function test_CheckIfUserUpdateReturnErrorIfBadRequestWithApi() {
        $response = $this->post(route('apiStoreUser', [
            'name' => 'Ariana',
            'email' => 'ariana@example.com',
            'password' => 'Ariana1234',
        ]));
        $data = ['email' => 'ariana@example.com'];

        $response->assertStatus(201);

        $response = $this->get(route('apiHomeUsers'));
        $response->assertStatus(200)
            ->assertJsonCount(1)
            ->assertJsonFragment($data);

        $response = $this->put(route('apiUpdateUser', 1), [
            'email' => 'ariana.martin@example.com',
            'password' => 1234,
        ]);
        $data = ['message' => 'Introduced data is not correct'];

        $response->assertStatus(400)
            ->assertJsonFragment($data);
    }

    public function test_CheckIfCanDeleteOneUserWithApi() {
        User::factory(2)->create();

        $response = $this->get(route('apiHomeUsers'));
        $response->assertStatus(200)
            ->assertJsonCount(2);

        $response = $this->delete(route('apiDestroyUser', 1));

        $response = $this->get(route('apiHomeUsers'));
        $response->assertStatus(200)
            ->assertJsonCount(1);
    }
}
