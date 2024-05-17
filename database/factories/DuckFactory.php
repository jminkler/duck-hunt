<?php
namespace Database\Factories;

use App\Models\Duck;
use Illuminate\Database\Eloquent\Factories\Factory;

class DuckFactory extends Factory
{
    protected $model = Duck::class;

    public function definition()
    {
        return [
            'name' => $this->faker->name,
            'speed' => $this->faker->randomFloat(2, 0, 10),
            'armor' => $this->faker->randomFloat(2, 0, 10),
            'evasiveness' => $this->faker->randomFloat(2, 0, 10),
            'health' => $this->faker->numberBetween(0, 100),
            'equipment' => [],
        ];
    }
}
