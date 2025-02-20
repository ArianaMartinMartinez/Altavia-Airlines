<?php

namespace Tests\Feature\Api;

use Tests\TestCase;
use App\Models\User;
use App\Models\Airplane;
use App\Models\Flight;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AirplaneTest extends TestCase
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

    public function test_CheckIfCanReceiveAllAirplanesWithApi() {
        $token = $this->authAdmin();
        Airplane::factory(5)->create();

        $response = $this->get(route('apiHomeAirplanes'), [
            'token' => $token,
        ]);
        $response->assertStatus(200)
            ->assertJsonCount(5);
    }

    public function test_CheckIfCanReceiveOneAirplaneWithApi() {
        $token = $this->authAdmin();
        $airplane = $this->post(route('apiStoreAirplane', [
            'token' => $token,
            'name' => 'A1B2C3',
            'seats' => 135,
        ]));
        $data = ['name' => 'A1B2C3'];

        $response = $this->get(route('apiShowAirplane', 1), [
            'token' => $token,
        ]);
        $response->assertStatus(200)
            ->assertJsonFragment($data);
    }

    public function test_CheckIfCanCreateNewAirplaneWithApi() {
        $token = $this->authAdmin();
        $airplane = $this->post(route('apiStoreAirplane', [
            'token' => $token,
            'name' => 'A1B2C3',
            'seats' => 135,
        ]));

        $response = $this->get(route('apiHomeAirplanes'), [
            'token' => $token,
        ]);
        $response->assertStatus(200)
            ->assertJsonCount(1);
    }

    public function test_CheckIfAirplaneCreateReturnErrorIfBadRequestWithApi() {
        $token = $this->authAdmin();
        $response = $this->post(route('apiStoreAirplane', [
            'token' => $token,
            'name' => 'A1B2C3',
        ]));

        $data = ['message' => 'Introduced data is not correct'];

        $response->assertStatus(400)
            ->assertJsonFragment($data);
    }

    public function test_CheckIfCanUpdateOneAirplaneWithApi() {
        $token = $this->authAdmin();
        $response = $this->post(route('apiStoreAirplane', [
            'token' => $token,
            'name' => 'A1B2C3',
            'seats' => 135,
        ]));
        $data = ['name' => 'A1B2C3'];

        $response->assertStatus(201);

        $response = $this->get(route('apiHomeAirplanes'), [
            'token' => $token,
        ]);
        $response->assertStatus(200)
            ->assertJsonCount(1)
            ->assertJsonFragment($data);

        $response = $this->put(route('apiUpdateAirplane', 1), [
            'token' => $token,
            'name' => 'A1B2C3D4',
            'seats' => 160,
        ]);
        $data = ['name' => 'A1B2C3D4'];

        $response = $this->get(route('apiHomeAirplanes'), [
            'token' => $token,
        ]);
        $response->assertStatus(200)
            ->assertJsonCount(1)
            ->assertJsonFragment($data);
    }

    public function test_CheckIfAirplaneUpdateReturnErrorIfBadRequestWithApi() {
        $token = $this->authAdmin();
        $response = $this->post(route('apiStoreAirplane', [
            'token' => $token,
            'name' => 'A1B2C3',
            'seats' => 135,
        ]));
        $data = ['name' => 'A1B2C3'];

        $response->assertStatus(201);

        $response = $this->get(route('apiHomeAirplanes'), [
            'token' => $token,
        ]);
        $response->assertStatus(200)
            ->assertJsonCount(1)
            ->assertJsonFragment($data);

        $response = $this->put(route('apiUpdateAirplane', 1), [
            'token' => $token,
            'seats' => 160,
        ]);
        $data = ['message' => 'Introduced data is not correct'];

        $response->assertStatus(400)
            ->assertJsonFragment($data);
    }

    public function test_CheckIfCanDeleteOneAirplaneWithApi() {
        $token = $this->authAdmin();
        Airplane::factory(2)->create();

        $response = $this->get(route('apiHomeAirplanes'), [
            'token' => $token,
        ]);
        $response->assertStatus(200)
            ->assertJsonCount(2);

        $response = $this->delete(route('apiDestroyAirplane', 1), [
            'token' => $token,
        ]);

        $response = $this->get(route('apiHomeAirplanes'), [
            'token' => $token,
        ]);
        $response->assertStatus(200)
            ->assertJsonCount(1);
    }

    public function test_CheckIfAirplaneHasManyFlightsWithApi() {
        $airplane = Airplane::factory()->create();

        $flight1 = Flight::factory()->create();
        $flight2 = Flight::factory()->create();

        $this->assertCount(2, $airplane->flights);
        $this->assertTrue($airplane->flights->contains($flight1));
        $this->assertTrue($airplane->flights->contains($flight2));
    }
}
