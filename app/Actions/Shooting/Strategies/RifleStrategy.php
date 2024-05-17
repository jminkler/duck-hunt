<?php

namespace App\Actions\Shooting\Strategies;

use App\Actions\Shooting\ShootDuckStrategy;
use App\Models\Duck;

class RifleStrategy implements ShootDuckStrategy
{
    public function shoot(Duck $duck): int
    {
        return $duck->takeDamage(rand(10, 50));
    }

    public function name(): string
    {
        return 'Rifle';
    }
}
