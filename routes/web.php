<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DasboardController;
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

Route::middleware(['guest'])
    ->group(function () {
        Route::get('auth/login', [AuthController::class, 'login'])
            ->name('login');
        Route::post('auth/process', [AuthController::class, 'auth'])
            ->name('auth.process');
    });

Route::middleware(['auth:web'])
    ->group(function () {

        Route::get('/', [DasboardController::class, 'index'])
            ->name('dashboard');

        Route::post('auth/logout', [AuthController::class, 'logout'])
            ->name('auth.logout');
    });
