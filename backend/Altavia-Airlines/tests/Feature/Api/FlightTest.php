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

    private function authUser() {
        $user = User::factory()->create([
            'name' => 'User',
            'email' => 'user@example.com',
            'password' => 'user',
            'role' => 'user',
        ]);

        $response = $this->post(route('login'), [
            'email' => $user->email,
            'password' => 'user',
        ]);

        return $response->json('access_token');
    }

    private function logout($token) {
        $response = $this->post(route('logout'), [
            'token' => $token,
        ]);

        return $response->json('message');
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

    public function test_CheckIfFlightCreateReturnErrorIfBadRequestWithSameCityWithApi() {
        $token = $this->authAdmin();
        Airplane::factory()->create();
        City::factory(5)->create();
        
        $response = $this->post(route('apiStoreFlight'), [
            'token' => $token,
            'date' => '2025-02-06',
            'price' => 200,
            'airplane_id' => 1,
            'departure_id' => 2,
            'arrival_id' => 2,
        ]);

        $data = ['message' => 'Departure and arrival cities must be different'];

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

    public function test_CheckIfCanBookAFlightWithApi() {
        $token = $this->authUser();
        Airplane::factory()->create();
        City::factory(5)->create();
        Flight::factory()->create();

        $response = $this->post(route('apiBookFlight', 1), [
            'token' => $token,
        ]);
        $data = ['message' => 'Flight booked successfully'];

        $response->assertStatus(200)
            ->assertJsonFragment($data);
    }

    public function test_CheckIfReturnErrorIfBookSameFlightWithApi() {
        $token = $this->authUser();
        Airplane::factory()->create();
        City::factory(5)->create();
        Flight::factory()->create();

        $response = $this->post(route('apiBookFlight', 1), [
            'token' => $token,
        ]);
        $data = ['message' => 'Flight booked successfully'];

        $response->assertStatus(200)
            ->assertJsonFragment($data);

        $response = $this->post(route('apiBookFlight', 1), [
            'token' => $token,
        ]);
        $data = ['message' => 'You have already booked this flight'];

        $response->assertStatus(400)
            ->assertJsonFragment($data);
    }

    public function test_CheckIfReturnErrorIfBookFlightWithNoSeatsWithApi() {
        $tokenAdmin = $this->authAdmin();
        Airplane::factory()->create([
            'name' => 'TestAirplane',
            'seats' => 1
        ]);
        City::factory(2)->create();
        Flight::factory()->create();

        $response = $this->post(route('apiBookFlight', 1), [
            'token' => $tokenAdmin,
        ]);
        $data = ['message' => 'Flight booked successfully'];

        $response->assertStatus(200)
            ->assertJsonFragment($data);
        
        $response = $this->logout($tokenAdmin);
        $tokenUser = $this->authUser();

        $response = $this->post(route('apiBookFlight', 1), [
            'token' => $tokenUser,
        ]);
        $data = ['message' => 'No seats available for this flight'];

        $response->assertStatus(400)
            ->assertJsonFragment($data);
    }

    public function test_CheckIfCanCancelAFlightWithApi() {
        $token = $this->authUser();
        Airplane::factory()->create();
        City::factory(2)->create();
        Flight::factory()->create();

        $response = $this->post(route('apiBookFlight', 1), [
            'token' => $token,
        ]);
        $data = ['message' => 'Flight booked successfully'];

        $response->assertStatus(200)
            ->assertJsonFragment($data);

        $response = $this->post(route('apiCancelFlight', 1), [
            'token' => $token,
        ]);
        $data = ['message' => 'Flight canceled successfully'];

        $response->assertStatus(200)
            ->assertJsonFragment($data);
    }

    public function test_CheckIfReturnErrorIfCancelNotBookedFlightWithApi() {
        $token = $this->authUser();
        Airplane::factory()->create();
        City::factory(2)->create();
        Flight::factory()->create();

        $response = $this->post(route('apiCancelFlight', 1), [
            'token' => $token,
        ]);
        $data = ['message' => "You haven't booked this flight"];

        $response->assertStatus(400)
            ->assertJsonFragment($data);
    }
}
