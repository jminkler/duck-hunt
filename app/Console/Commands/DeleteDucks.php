<?php

namespace App\Console\Commands;

use App\Models\Duck;
use Illuminate\Console\Command;


class DeleteDucks extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:delete-ducks';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete all ducks from the database.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Deleting all ducks from the database...');

        Duck::truncate();

        $this->info('All ducks have been deleted from the database.');
    }
}
