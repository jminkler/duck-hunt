<?php

namespace App\Actions\Medical;

class Doctor extends MedicalAction
{
    public const HEALS_FOR = 40;

    public function healsFor(): int
    {
        return self::HEALS_FOR;
    }
}
