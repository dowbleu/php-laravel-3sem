<?php

use App\Http\Controllers\MainController;
use App\Http\Controllers\AuthController;
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

//Auth
Route::get('/auth/signin', [AuthController::class, 'signin']);
Route::post('/auth/register', [AuthController::class, 'register']);


//Main
Route::get('/', [MainController::class, 'index']);
Route::get('/full_image/{img}', [MainController::class, 'show']);

Route::get('/about', function() {
    return view('main.about');
});

Route::get('/contact', function() {
    $array = [
        'name' => 'Moscow Polytech',
        'adress' => 'B. Semenovskaya h.38',
        'email' => '..@mospolytech.ru',
        'phone' => '8(499)232-2222'
    ];
    return view('main.contact', ['contact' => $array]);
});