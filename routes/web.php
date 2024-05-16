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
