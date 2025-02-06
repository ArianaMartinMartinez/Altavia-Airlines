<?php

namespace Tests\Feature\Api;

use Tests\TestCase;
use App\Models\Flight;
use App\Models\Airplane;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class FlightTest extends TestCase
{
    use RefreshDatabase;

    public function test_CheckIfCanReceiveAllFlightsWihtApi() {
        Airplane::factory(5)->create();
        Flight::factory(3)->create();

        $response = $this->get(route('apiHomeFlights'));
        $response->assertStatus(200)
            ->assertJsonCount(3);
    }

    public function test_CheckIfCanReceiveOneFlightWithApi() {
        Airplane::factory()->create();
        
        $flight = $this->post(route('apiStoreFlight'), [
            'date' => '2025-02-06',
            'price' => 200,
            'airplane_id' => 1,
        ]);
        $data = ['date' => '2025-02-06'];

        $response = $this->get(route('apiShowFlight', 1));
        $response->assertStatus(200)
            ->assertJsonFragment($data);
    }

    public function test_CheckIfCanCreateNewFlightWithApi() {
        Airplane::factory()->create();
        
        $flight = $this->post(route('apiStoreFlight'), [
            'date' => '2025-02-06',
            'price' => 200,
            'airplane_id' => 1,
        ]);

        $response = $this->get(route('apiHomeFlights'));
        $response->assertStatus(200)
            ->assertJsonCount(1);
    }

    public function test_CheckIfFlightCreateReturnErrorIfBadRequestWithApi() {
        Airplane::factory()->create();
        
        $response = $this->post(route('apiStoreFlight'), [
            'date' => 2025-02-06,
            'price' => '200',
            'airplane_id' => 10,
        ]);

        $data = ['message' => 'Introduced data is not correct'];

        $response->assertStatus(400)
            ->assertJsonFragment($data);
    }

    public function test_CheckIfCanUpdateOneFlightWithApi() {
        Airplane::factory()->create();
        $response = $this->post(route('apiStoreFlight', [
            'date' => '2025-02-06',
            'price' => 200,
            'airplane_id' => 1,
        ]));
        $data = ['date' => '2025-02-06'];

        $response->assertStatus(201);

        $response = $this->get(route('apiHomeFlights'));
        $response->assertStatus(200)
            ->assertJsonCount(1)
            ->assertJsonFragment($data);

        $response = $this->put(route('apiUpdateFlight', 1), [
            'date' => '2024-05-10',
            'price' => 115,
            'airplane_id' => 1,
        ]);
        $data = ['date' => '2024-05-10'];

        $response = $this->get(route('apiHomeFlights'));
        $response->assertStatus(200)
            ->assertJsonCount(1)
            ->assertJsonFragment($data);
    }

    public function test_CheckIfFlightUpdateReturnErrorIfBadRequestWithApi() {
        Airplane::factory()->create();
        $response = $this->post(route('apiStoreFlight', [
            'date' => '2025-02-06',
            'price' => 200,
            'airplane_id' => 1,
        ]));
        $data = ['date' => '2025-02-06'];

        $response->assertStatus(201);

        $response = $this->get(route('apiHomeFlights'));
        $response->assertStatus(200)
            ->assertJsonCount(1)
            ->assertJsonFragment($data);

        $response = $this->put(route('apiUpdateFlight', 1), [
            'date' => 2025-02-06,
            'price' => '200',
            'airplane_id' => 10,
        ]);
        $data = ['message' => 'Introduced data is not correct'];

        $response->assertStatus(400)
            ->assertJsonFragment($data);
    }

    public function test_CheckIfCanDeleteOneFlightWithApi() {
        Airplane::factory()->create();
        Flight::factory(2)->create();

        $response = $this->get(route('apiHomeFlights'));
        $response->assertStatus(200)
            ->assertJsonCount(2);

        $response = $this->delete(route('apiDestroyFlight', 1));

        $response = $this->get(route('apiHomeFlights'));
        $response->assertStatus(200)
            ->assertJsonCount(1);
    }
}
