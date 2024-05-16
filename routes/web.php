<?php

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
Route::get('/stats', function () {
    $stats = Duck::raw(function ($collection) {
        return $collection->aggregate([
            [
                '$facet' => [
                    'totalDucks' => [
                        [
                            '$count' => 'count'
                        ]
                    ],
                    'injuredDucks' => [
                        [
                            '$match' => ['injured' => true]
                        ],
                        [
                            '$count' => 'count'
                        ]
                    ],
                    'averageSpeed' => [
                        [
                            '$group' => [
                                '_id' => null,
                                'averageSpeed' => ['$avg' => '$speed']
                            ]
                        ]
                    ],
                    'evasivenessEquipment' => [
                        [
                            '$unwind' => '$equipment'
                        ],
                        [
                            '$match' => ['equipment.type' => 'evasiveness']
                        ],
                        [
                            '$count' => 'count'
                        ]
                    ]
                ]
            ]
        ]);
    })->toArray();

    $totalDucks = $stats[0]['totalDucks'][0]['count'] ?? 0;
    $injuredDucks = $stats[0]['injuredDucks'][0]['count'] ?? 0;
    $averageSpeed = $stats[0]['averageSpeed'][0]['averageSpeed'] ?? 0;
    $evasivenessEquipment = $stats[0]['evasivenessEquipment'][0]['count'] ?? 0;

    return response()->json([
        'totalDucks' => $totalDucks,
        'injuredDucks' => $injuredDucks,
        'averageSpeed' => $averageSpeed,
        'evasivenessEquipment' => $evasivenessEquipment,
    ]);
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
