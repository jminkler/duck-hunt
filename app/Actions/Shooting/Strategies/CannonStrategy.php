<?php

namespace App\Actions\Shooting\Strategies;

use App\Actions\Shooting\ShootDuckStrategy;
use App\Models\Duck;

class CannonStrategy implements ShootDuckStrategy
{
    public function shoot(Duck $duck): int
    {
        return $duck->takeDamage(rand(50, 100));
    }

    public function name(): string
    {
        return 'Cannon';
    }
}
