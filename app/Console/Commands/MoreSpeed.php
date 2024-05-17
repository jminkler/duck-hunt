<?php

namespace App\Console\Commands;

use App\Models\Duck;
use Illuminate\Console\Command;

class MoreSpeed extends Command
{
    protected $signature = 'app:more-speed';

    protected $description = 'Give all ducks more speed, to a max of 10';

    public function handle()
    {
        $this->line("Giving all ducks more speed...");
        $bar = $this->output->createProgressBar(Duck::count());
        $bar->start();
        Duck::chunk(1000, function ($ducks) use ($bar) {
            foreach ($ducks as $duck) {
                $newSpeed = min(10, $duck->speed + 1); // Example: Increase health by 1, to a max of 10
                $duck->update(['health' => $newSpeed]);
                $bar->advance();
            }
        });
        $bar->finish();
    }
}
