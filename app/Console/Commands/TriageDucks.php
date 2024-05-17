<?php

namespace App\Console\Commands;

use App\Jobs\TriageDuck;
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
            $this->line("Triaging {$duck->name}...");
            TriageDuck::dispatch($duck->_id);
        });

        $this->line("All injured ducks have been triaged.");
    }
}
