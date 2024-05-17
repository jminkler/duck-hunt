<?php

namespace App\Actions\Medical;

use App\Models\Duck;

interface HealthcareDuckInterface
{
    public function heal(Duck $duck): void;
    public function healsFor(): int;


}
