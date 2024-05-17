<?php

use App\Query\DuckStatsQuery;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

use App\Models\Duck;
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
