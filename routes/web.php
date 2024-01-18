<?php

declare(strict_types=1);

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

// Send fortify to SPA to handle password reset
Route::get('/reset-password', function () {
    return view('main');
})->name('password.reset');

Route::view('{route?}', 'main')->where('route', '(?!api|assets|sanctum).*');
