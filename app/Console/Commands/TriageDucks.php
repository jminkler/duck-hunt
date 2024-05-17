<?php

namespace App\Console\Commands;

use App\Actions\Medical\Doctor;
use App\Jobs\DoctorJob;
use App\Jobs\MedicJob;
use App\Models\Duck;
use Illuminate\Console\Command;

class TriageDucks extends Command
{
    protected $signature = 'app:triage-ducks';

    protected $description = 'Triage all Injured Ducks, and send them to appropriate care.';

    public function handle()
    {
        $this->line('Triaging all injured ducks...');

        $count = Duck::injured()->count();
        $this->line("There are $count injured ducks.");

        Duck::injured()->each(function ($duck) {
            if (! $duck->isSeriouslyInjured()) {
                $this->line("Sending {$duck->name} to the Medic.");
                dispatch(new MedicJob($duck->_id));
                return;
            }

            $this->line("Sending {$duck->name} to the Doctor.");
            dispatch(new DoctorJob($duck->_id));

        });

        $this->line("All injured ducks have been triaged.");
    }
}
