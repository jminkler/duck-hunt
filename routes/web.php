<?php

use App\Query\DuckStatsQuery;
use App\Models\Duck;
use Illuminate\Support\Facades\Route;
use MongoDB\Client as MongoClient;
use MongoDB\BSON\ObjectId;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/ducks', function () {

    $ducks = Duck::where('health', '>', 80)
        ->take(10)
        ->get()->all();

    return response()->json(
        $ducks
    );
});

Route::get('/stats', function (DuckStatsQuery $duckQuery) {
    return response()->json($duckQuery->stats());
});

Route::get('/search-ducks', function () {
    // Only do health filtering by default
    $health = request('health', 90);
    $ducks = Duck::withHealthAt((float) $health);

    if (request()->has('speed')) {
        $ducks->withSpeedAbove((float) request('speed'));
    }

    if (request()->has('totalSpeed')) {
        $ducks->withTotalSpeedAbove((float) request('totalSpeed'));
    }

    if (request()->has('date')) {
        $ducks->createdAfter(request('date'));
    }

    if (request()->has('equipment')) {
        $ducks->withEquipmentType(request('equipment'));
    }

    $ducks->orderBy('health', 'asc');

    return response()->json($ducks->paginate(request('perPage', 100)));
});
/**
 * Check if the equipment is used by any duck
 *
 * Check if the mql query is using the index we setup in the migration
 *
 */
Route::get('/explain-ducks-with-equipment/{equipmentId}', function ($equipmentId) {
    // MongoDB Laravel builder does not support explain() method
    $client = new MongoClient(env('DB_CONNECTION_STRING'));

    $query = ['equipment._id' => new ObjectId($equipmentId)];
    $command = [
        'explain' => [
            'find' => 'ducks',
            'filter' => $query
        ]
    ];

    $database = $client->selectDatabase(env('DB_DATABASE'));
    $explainResult = $database->command($command)->toArray();

    return response()->json($explainResult);
});
