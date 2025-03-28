<?php

namespace Database\Seeders;

use App\Models\Airplane;
use App\Models\City;
use App\Models\Flight;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory()->create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'password' => 'admin',
            'role' => 'admin',
        ]);

        User::factory()->create([
            'name' => 'User',
            'email' => 'user@example.com',
            'password' => 'user',
            'role' => 'user',
        ]);

        City::factory(100)->create();

        Airplane::factory(15)->create();

        Flight::factory(25)->create();

        User::factory(30)->create()->each(function($user) {
            $flights = Flight::all()->random()->limit(rand(1,10))->pluck('id');
            $user->flights()->attach($flights);
        });
    }
}
