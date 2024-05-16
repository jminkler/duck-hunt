<?php

namespace Database\Seeders;

use App\Models\Duck;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class DuckSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();
        $ducks = [];

        $now = Carbon::now()->format('Y-m-d H:i:s');

        for ($i = 0; $i < 100000; $i++) {
            $ducks[] = [
                'name' => $faker->name,
                'speed' => $faker->randomFloat(2, 0.1, 10),
                'armor' => $faker->randomFloat(2, 0, 10),
                'evasiveness' => $faker->randomFloat(2, 0, 10),
                'health' => 100,
                'created_at' => $now,
                'updated_at' => $now,
            ];

            if ($i % 1000 === 0) {
                Duck::insert($ducks);
                $ducks = [];
            }
        }

        Duck::insert($ducks);
    }
}
