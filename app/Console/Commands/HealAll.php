<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Duck;

class HealAll extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:heal-all';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Heal all Injured Ducks.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->line('Healing all injured ducks...');
        $count = Duck::injured()->count();
        $this->line("There are $count injured ducks.");

        Duck::injured()->update(['health' => 100]);

        $this->line("All injured ducks have been healed.");
    }
}
