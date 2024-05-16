<?php

namespace Database\Seeders;

use App\Models\Duck;
use App\Models\Equipment;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        Equipment::truncate();
        Duck::truncate();

        $this->call([
            EquipmentSeeder::class,
            DuckSeeder::class,
        ]);
    }
}
