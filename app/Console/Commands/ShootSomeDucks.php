<?php

namespace App\Console\Commands;

use App\Models\Duck;
use App\Query\RandomSampleQuery;
use Illuminate\Console\Command;

class ShootSomeDucks extends Command
{
    protected $signature = 'app:shoot-some-ducks {count=10}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Testing command to apply damage to some ducks.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->line('Shooting some ducks...');

        $ducks = (new RandomSampleQuery(new Duck))
            ->execute($this->argument('count'));

        $ducks->each(function ($duck) {
            $damage = rand(1, 100);
            $this->line("Shooting {$duck->name} for $damage damage.");

            $duck->takeDamage($damage); // allows for armor to absorb some damage
            $duck->save();
        });

        $this->line('All ducks have been shot.');
    }
}
