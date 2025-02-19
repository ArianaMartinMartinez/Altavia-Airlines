<?php

namespace Tests\Feature\Api;

use Tests\TestCase;
use App\Models\City;
use App\Models\User;
use App\Models\Flight;
use App\Models\Airplane;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class FlightTest extends TestCase
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

    public function test_CheckIfCanReceiveAllFlightsWihtApi() {
        Airplane::factory(5)->create();
        Flight::factory(3)->create();

        $response = $this->get(route('apiHomeFlights'));
        $response->assertStatus(200)
            ->assertJsonCount(3);
    }

    public function test_CheckIfCanReceiveOneFlightWithApi() {
        $token = $this->authAdmin();
        Airplane::factory()->create();
        City::factory(5)->create();
        
        $flight = $this->post(route('apiStoreFlight'), [
            'token' => $token,
            'date' => '2025-02-06',
            'price' => 200,
            'airplane_id' => 1,
            'departure_id' => 2,
            'arrival_id' => 5,
        ]);
        $data = ['date' => '2025-02-06'];

        $response = $this->get(route('apiShowFlight', 1), [
            'token' => $token,
        ]);
        $response->assertStatus(200)
            ->assertJsonFragment($data);
    }

    public function test_CheckIfCanCreateNewFlightWithApi() {
        $token = $this->authAdmin();
        Airplane::factory()->create();
        City::factory(5)->create();
        
        $flight = $this->post(route('apiStoreFlight'), [
            'token' => $token,
            'date' => '2025-02-06',
            'price' => 200,
            'airplane_id' => 1,
            'departure_id' => 2,
            'arrival_id' => 5,
        ]);

        $response = $this->get(route('apiHomeFlights'));
        $response->assertStatus(200)
            ->assertJsonCount(1);
    }

    public function test_CheckIfFlightCreateReturnErrorIfBadRequestWithApi() {
        $token = $this->authAdmin();
        Airplane::factory()->create();
        City::factory(5)->create();
        
        $response = $this->post(route('apiStoreFlight'), [
            'token' => $token,
            'date' => 2025-02-06,
            'price' => '200',
            'airplane_id' => 10,
            'departure_id' => '2',
            'arrival_id' => '5',
        ]);

        $data = ['message' => 'Introduced data is not correct'];

        $response->assertStatus(400)
            ->assertJsonFragment($data);
    }

    public function test_CheckIfCanUpdateOneFlightWithApi() {
        $token = $this->authAdmin();
        Airplane::factory()->create();
        City::factory(5)->create();

        $response = $this->post(route('apiStoreFlight', [
            'token' => $token,
            'date' => '2025-02-06',
            'price' => 200,
            'airplane_id' => 1,
            'departure_id' => 2,
            'arrival_id' => 5,
        ]));
        $data = ['date' => '2025-02-06'];

        $response->assertStatus(201);

        $response = $this->get(route('apiHomeFlights'));
        $response->assertStatus(200)
            ->assertJsonCount(1)
            ->assertJsonFragment($data);

        $response = $this->put(route('apiUpdateFlight', 1), [
            'token' => $token,
            'date' => '2024-05-10',
            'price' => 115,
            'airplane_id' => 1,
            'departure_id' => 3,
            'arrival_id' => 1,
        ]);
        $data = ['date' => '2024-05-10'];

        $response = $this->get(route('apiHomeFlights'));
        $response->assertStatus(200)
            ->assertJsonCount(1)
            ->assertJsonFragment($data);
    }

    public function test_CheckIfFlightUpdateReturnErrorIfBadRequestWithApi() {
        $token = $this->authAdmin();
        Airplane::factory()->create();
        City::factory(5)->create();

        $response = $this->post(route('apiStoreFlight', [
            'token' => $token,
            'date' => '2025-02-06',
            'price' => 200,
            'airplane_id' => 1,
            'departure_id' => 2,
            'arrival_id' => 5,
        ]));
        $data = ['date' => '2025-02-06'];

        $response->assertStatus(201);

        $response = $this->get(route('apiHomeFlights'));
        $response->assertStatus(200)
            ->assertJsonCount(1)
            ->assertJsonFragment($data);

        $response = $this->put(route('apiUpdateFlight', 1), [
            'token' => $token,
            'date' => 2025-02-06,
            'price' => '200',
            'airplane_id' => 10,
            'departure_id' => '2',
            'arrival_id' => '5',
        ]);
        $data = ['message' => 'Introduced data is not correct'];

        $response->assertStatus(400)
            ->assertJsonFragment($data);
    }

    public function test_CheckIfCanDeleteOneFlightWithApi() {
        $token = $this->authAdmin();
        Airplane::factory()->create();
        City::factory(5)->create();
        Flight::factory(2)->create();

        $response = $this->get(route('apiHomeFlights'));
        $response->assertStatus(200)
            ->assertJsonCount(2);

        $response = $this->delete(route('apiDestroyFlight', 1), [
            'token' => $token,
        ]);

        $response = $this->get(route('apiHomeFlights'));
        $response->assertStatus(200)
            ->assertJsonCount(1);
    }
}
