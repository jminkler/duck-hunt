<?php

namespace App\Actions\Medical;

use App\Models\Duck;

abstract class MedicalAction implements HealthcareDuckInterface
{
    public function heal(Duck $duck): void
    {
        $duck->health = min(
            Duck::MAX_HEALTH,
            $duck->health + ($this->healsFor() ?? 0)
        );
        $duck->save();
    }

    abstract function healsFor(): int;
}
