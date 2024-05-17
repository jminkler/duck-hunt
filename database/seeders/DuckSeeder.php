<?php

namespace Database\Seeders;

use App\Models\Duck;
use App\Models\Equipment;
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

        $equipment = Equipment::all()->toArray();

        $now = Carbon::now()->format('Y-m-d H:i:s');

        for ($i = 0; $i < 100000; $i++) {
            $randomEquipment = $faker->randomElements($equipment, $faker->numberBetween(1, 3));
            $formattedEquipment = array_map(function ($item) {
                return (array) $item;
            }, $randomEquipment);

            $ducks[] = [
                'name' => $faker->name,
                'speed' => $faker->randomFloat(2, 0.1, 10),
                'armor' => $faker->randomFloat(2, 0, 10),
                'evasiveness' => $faker->randomFloat(2, 0, 10),
                'health' => $faker->numberBetween(1, 100),
                'equipment' => $formattedEquipment,
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
