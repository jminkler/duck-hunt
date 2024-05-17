<?php

namespace App\Actions\Shooting;

use App\Actions\Shooting\Strategies\GunStrategy;
use App\Actions\Shooting\Strategies\RifleStrategy;
use App\Actions\Shooting\Strategies\CannonStrategy;


class ShootDuckActionFactory
{
    protected $strategies = [
        GunStrategy::class,
        RifleStrategy::class,
        CannonStrategy::class,
    ];

    public function make()
    {
        $strategyClass = $this->strategies[array_rand($this->strategies)];
        return new $strategyClass;
    }
}
