<?php

namespace App\Actions\Shooting\Strategies;

use App\Actions\Shooting\ShootDuckStrategy;
use App\Models\Duck;

class GunStrategy implements ShootDuckStrategy
{
    public function shoot(Duck $duck): int
    {
        return $duck->takeDamage(rand(1, 10));
    }

    public function name(): string
    {
        return 'Gun';
    }

}
