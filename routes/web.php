<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HelloWorldController;
use App\Http\Controllers\DiceController;
use App\Http\Controllers\DiceHandController;
use App\Http\Controllers\Game21Controller;
use App\Http\Controllers\YatzyController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/welcome', function () {
    return view('welcome');
});

Route::get('/', function () {
    return redirect('/home');
});

Route::get('/home', function () {
    return view('home');
});

Route::match(['get', 'post'], '/dice', [DiceController::class, 'show']);
Route::match(['get', 'post'], '/dicehand', [DiceHandController::class, 'show']);

// Route::get('/game21', function () {
//     return view('game21');
// });
Route::match(['get', 'post'], '/game21', [Game21Controller::class, 'show']);

// Route::get('/yatzy', function () {
//     return view('yatzy');
// });
Route::match(['get', 'post'], '/yatzy', [YatzyController::class, 'show']);


// Added for mos example code
Route::get('/hello-world', function () {
    echo "Hello World";
});
Route::get('/hello-world-view', function () {
    return view('message', [
        'message' => "Hello World from within a view"
    ]);
});
Route::get('/hello', [HelloWorldController::class, 'hello']);
Route::get('/hello/{message}', [HelloWorldController::class, 'hello']);
