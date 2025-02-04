<?php

namespace Tests\Feature\Api;

use App\Models\City;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CityTest extends TestCase
{
    use RefreshDatabase;

    public function test_CheckIfCanReceiveAllCitiesWithApi() {
        City::factory(5)->create();

        $response = $this->get(route('apiHomeCities'));
        $response->assertStatus(200)
            ->assertJsonCount(5);
    }

    public function test_CheckIfCanReceiveOneCityWithApi() {
        $city = $this->post(route('apiStoreCity', [
            'name' => 'Málaga',
        ]));
        $data = ['name' => 'Málaga'];

        $response = $this->get(route('apiShowCity', 1));
        $response->assertStatus(200)
            ->assertJsonFragment($data);
    }

    public function test_CheckIfCanCreateNewUserWithApi() {
        $city = $this->post(route('apiStoreCity', [
            'name' => 'Málaga',
        ]));

        $response = $this->get(route('apiHomeCities'));
        $response->assertStatus(200)
            ->assertJsonCount(1);
    }

    public function test_CheckIfCityCreateReturnErrorIfBadRequestWithApi() {
        $response = $this->post(route('apiStoreCity', []));

        $data = ['message' => 'Introduced data is not correct'];

        $response->assertStatus(400)
            ->assertJsonFragment($data);
    }

    public function test_CheckIfCanUpdateOneCityWithApi() {
        $response = $this->post(route('apiStoreCity', [
            'name' => 'Málaga',
        ]));
        $data = ['name' => 'Málaga'];

        $response->assertStatus(201);

        $response = $this->get(route('apiHomeCities'));
        $response->assertStatus(200)
            ->assertJsonCount(1)
            ->assertJsonFragment($data);

        $response = $this->put(route('apiUpdateCity', 1), [
            'name' => 'Málaga updated'
        ]);
        $data = ['name' => 'Málaga updated'];

        $response = $this->get(route('apiHomeCities'));
        $response->assertStatus(200)
            ->assertJsonCount(1)
            ->assertJsonFragment($data);
    }

    public function test_CheckIfCityUpdateReturnErrorIfBadRequestWithApi() {
        $response = $this->post(route('apiStoreCity', [
            'name' => 'Málaga',
        ]));
        $data = ['name' => 'Málaga'];

        $response->assertStatus(201);

        $response = $this->get(route('apiHomeCities'));
        $response->assertStatus(200)
            ->assertJsonCount(1)
            ->assertJsonFragment($data);

        $response = $this->put(route('apiUpdateCity', 1), []);
        $data = ['message' => 'Introduced data is not correct'];

        $response->assertStatus(400)
            ->assertJsonFragment($data);
    }

    public function test_CheckIfCanDeleteOneCityWithApi() {
        City::factory(2)->create();

        $response = $this->get(route('apiHomeCities'));
        $response->assertStatus(200)
            ->assertJsonCount(2);

        $response = $this->delete(route('apiDestroyCity', 1));

        $response = $this->get(route('apiHomeCities'));
        $response->assertStatus(200)
            ->assertJsonCount(1);
    }
}
