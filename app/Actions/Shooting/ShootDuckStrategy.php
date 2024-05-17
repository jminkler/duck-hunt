<?php

namespace App\Actions\Shooting;

use App\Models\Duck;

interface ShootDuckStrategy
{
    public function shoot(Duck $duck): int;
    public function name(): string;
}
