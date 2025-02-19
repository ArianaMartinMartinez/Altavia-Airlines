<?php

namespace Tests\Feature\Api;

use App\Models\City;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CityTest extends TestCase
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

    public function test_CheckIfCanReceiveAllCitiesWithApi() {
        City::factory(5)->create();

        $response = $this->get(route('apiHomeCities'));
        $response->assertStatus(200)
            ->assertJsonCount(5);
    }

    public function test_CheckIfCanReceiveOneCityWithApi() {
        $token = $this->authAdmin();
        $city = $this->post(route('apiStoreCity', [
            'token' => $token,
            'name' => 'Málaga',
        ]));
        $data = ['name' => 'Málaga'];

        $response = $this->get(route('apiShowCity', 1));
        $response->assertStatus(200)
            ->assertJsonFragment($data);
    }

    public function test_CheckIfCanCreateNewUserWithApi() {
        $token = $this->authAdmin();
        $city = $this->post(route('apiStoreCity', [
            'token' => $token,
            'name' => 'Málaga',
        ]));

        $response = $this->get(route('apiHomeCities'));
        $response->assertStatus(200)
            ->assertJsonCount(1);
    }

    public function test_CheckIfCityCreateReturnErrorIfBadRequestWithApi() {
        $token = $this->authAdmin();
        $response = $this->post(route('apiStoreCity', [
            'token' => $token,
        ]));

        $data = ['message' => 'Introduced data is not correct'];

        $response->assertStatus(400)
            ->assertJsonFragment($data);
    }

    public function test_CheckIfCanUpdateOneCityWithApi() {
        $token = $this->authAdmin();
        $response = $this->post(route('apiStoreCity', [
            'token' => $token,
            'name' => 'Málaga',
        ]));
        $data = ['name' => 'Málaga'];

        $response->assertStatus(201);

        $response = $this->get(route('apiHomeCities'));
        $response->assertStatus(200)
            ->assertJsonCount(1)
            ->assertJsonFragment($data);

        $response = $this->put(route('apiUpdateCity', 1), [
            'token' => $token,
            'name' => 'Málaga updated'
        ]);
        $data = ['name' => 'Málaga updated'];

        $response = $this->get(route('apiHomeCities'));
        $response->assertStatus(200)
            ->assertJsonCount(1)
            ->assertJsonFragment($data);
    }

    public function test_CheckIfCityUpdateReturnErrorIfBadRequestWithApi() {
        $token = $this->authAdmin();
        $response = $this->post(route('apiStoreCity', [
            'token' => $token,
            'name' => 'Málaga',
        ]));
        $data = ['name' => 'Málaga'];

        $response->assertStatus(201);

        $response = $this->get(route('apiHomeCities'));
        $response->assertStatus(200)
            ->assertJsonCount(1)
            ->assertJsonFragment($data);

        $response = $this->put(route('apiUpdateCity', 1), [
            'token' => $token,
        ]);
        $data = ['message' => 'Introduced data is not correct'];

        $response->assertStatus(400)
            ->assertJsonFragment($data);
    }

    public function test_CheckIfCanDeleteOneCityWithApi() {
        $token = $this->authAdmin();
        City::factory(2)->create();

        $response = $this->get(route('apiHomeCities'));
        $response->assertStatus(200)
            ->assertJsonCount(2);

        $response = $this->delete(route('apiDestroyCity', 1), [
            'token' => $token,
        ]);

        $response = $this->get(route('apiHomeCities'));
        $response->assertStatus(200)
            ->assertJsonCount(1);
    }
}
