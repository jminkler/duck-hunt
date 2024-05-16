<?php

namespace Database\Seeders;

use App\Models\Equipment;
use Illuminate\Database\Seeder;

class EquipmentSeeder extends Seeder
{
    public function run(): void
    {
        $equipment = [
            ['name' => 'SpeedBoots', 'type' => 'speed', 'value' => 5],
            ['name' => 'ArmorVest', 'type' => 'armor', 'value' => 5],
            ['name' => 'EvasionCloak', 'type' => 'evasiveness', 'value' => 5],
            ['name' => 'StealthSuit', 'type' => 'evasiveness', 'value' => 7],
            ['name' => 'IronShield', 'type' => 'armor', 'value' => 7],
            ['name' => 'TurboBoosters', 'type' => 'speed', 'value' => 7],
        ];

        Equipment::insert($equipment);
    }
}
