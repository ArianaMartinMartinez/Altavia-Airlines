<?php

namespace Tests\Feature\Api;

use App\Models\Airplane;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AirplaneTest extends TestCase
{
    use RefreshDatabase;

    public function test_CheckIfCanReceiveAllAirplanesWithApi() {
        Airplane::factory(5)->create();

        $response = $this->get(route('apiHomeAirplanes'));
        $response->assertStatus(200)
            ->assertJsonCount(5);
    }

    public function test_CheckIfCanReceiveOneAirplaneWithApi() {
        $airplane = $this->post(route('apiStoreAirplane', [
            'name' => 'A1B2C3',
            'seats' => 135,
        ]));
        $data = ['name' => 'A1B2C3'];

        $response = $this->get(route('apiShowAirplane', 1));
        $response->assertStatus(200)
            ->assertJsonFragment($data);
    }

    public function test_CheckIfCanCreateNewAirplaneWithApi() {
        $airplane = $this->post(route('apiStoreAirplane', [
            'name' => 'A1B2C3',
            'seats' => 135,
        ]));

        $response = $this->get(route('apiHomeAirplanes'));
        $response->assertStatus(200)
            ->assertJsonCount(1);
    }

    public function test_CheckIfAirplaneCreateReturnErrorIfBadRequestWithApi() {
        $response = $this->post(route('apiStoreAirplane', [
            'name' => 'A1B2C3',
        ]));

        $data = ['message' => 'Introduced data is not correct'];

        $response->assertStatus(400)
            ->assertJsonFragment($data);
    }

    public function test_CheckIfCanUpdateOneAirplaneWithApi() {
        $response = $this->post(route('apiStoreAirplane', [
            'name' => 'A1B2C3',
            'seats' => 135,
        ]));
        $data = ['name' => 'A1B2C3'];

        $response->assertStatus(201);

        $response = $this->get(route('apiHomeAirplanes'));
        $response->assertStatus(200)
            ->assertJsonCount(1)
            ->assertJsonFragment($data);

        $response = $this->put(route('apiUpdateAirplane', 1), [
            'name' => 'A1B2C3D4',
            'seats' => 160,
        ]);
        $data = ['name' => 'A1B2C3D4'];

        $response = $this->get(route('apiHomeAirplanes'));
        $response->assertStatus(200)
            ->assertJsonCount(1)
            ->assertJsonFragment($data);
    }

    public function test_CheckIfAirplaneUpdateReturnErrorIfBadRequestWithApi() {
        $response = $this->post(route('apiStoreAirplane', [
            'name' => 'A1B2C3',
            'seats' => 135,
        ]));
        $data = ['name' => 'A1B2C3'];

        $response->assertStatus(201);

        $response = $this->get(route('apiHomeAirplanes'));
        $response->assertStatus(200)
            ->assertJsonCount(1)
            ->assertJsonFragment($data);

        $response = $this->put(route('apiUpdateAirplane', 1), [
            'seats' => 160,
        ]);
        $data = ['message' => 'Introduced data is not correct'];

        $response->assertStatus(400)
            ->assertJsonFragment($data);
    }

    public function test_CheckIfCanDeleteOneAirplaneWithApi() {
        Airplane::factory(2)->create();

        $response = $this->get(route('apiHomeAirplanes'));
        $response->assertStatus(200)
            ->assertJsonCount(2);

        $response = $this->delete(route('apiDestroyAirplane', 1));

        $response = $this->get(route('apiHomeAirplanes'));
        $response->assertStatus(200)
            ->assertJsonCount(1);
    }
}
