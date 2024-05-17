<?php

namespace App\Console\Commands;

use App\Actions\Shooting\ShootDuckActionFactory;
use App\Models\Duck;
use App\Query\DuckStatsQuery;
use App\Query\RandomSampleQuery;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;

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
    public function handle(ShootDuckActionFactory $shootingStrategyFactory)
    {
        $this->line('Shooting some ducks...');

        $ducks = (new RandomSampleQuery(new Duck))
            ->execute($this->argument('count'));

        $ducks->each(function ($duck) use ($shootingStrategyFactory) {
            $shootingStrategy = $shootingStrategyFactory->make();
            $this->line("Shooting {$duck->name} with a {$shootingStrategy->name()}!.");

            $damageTaken = $shootingStrategy->shoot($duck);
            $this->line("{$duck->name} took {$damageTaken} damage.");
        });

        // Clear the stats cache, just so we can see the damage
        Cache::forget(DuckStatsQuery::STATS_CACHE_KEY);

        $this->line('All ducks have been shot.');
    }
}
