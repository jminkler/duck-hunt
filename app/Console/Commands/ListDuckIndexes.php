<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use MongoDB\Client as MongoClient;

class ListDuckIndexes extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:list-duck-indexes';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'List all the indexes on the ducks collection.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $client = new MongoClient(env('DB_CONNECTION_STRING'));
        $collection = $client->selectCollection(env('DB_DATABASE'), 'ducks');

        $indexes = collect($collection->listIndexes());

        dd($indexes);
        $this->table(
            ['Name', 'Key', 'Unique'],
            $indexes->map(function ($index) {
                return [
                    $index->getName(),
                    $index->getKey(),
                    $index->isUnique(),
                ];
            })
        );
    }
}
