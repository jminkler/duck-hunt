<?php

namespace App\Actions\Medical;

class Medic extends MedicalAction
{
    public const HEALS_FOR = 20;

    public function healsFor(): int
    {
        return self::HEALS_FOR;
    }
}
