<?php

namespace Database\Factories;

use App\Models\Airplane;
use App\Models\City;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Flight>
 */
class FlightFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $departure = City::inRandomOrder()->first() ?? City::factory()->create();
        $arrival = City::inRandomOrder()->where('id', '!=', $departure->id)->first();
        if (!$arrival) {
            $arrival = City::factory()->create();
        }

        return [
            'date' => $this->faker->date(),
            'price' => $this->faker->randomFloat(2),
            'airplane_id' => Airplane::all()->random()->id,
            'departure_id' => $departure->id,
            'arrival_id' => $arrival->id,
        ];
    }
}
