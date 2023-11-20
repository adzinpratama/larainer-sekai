<?php

use App\Http\Controllers\Api\AuthApiController;
use Illuminate\Support\Facades\Route;

Route::prefix('auth')
    ->controller(AuthApiController::class)
    ->group(function () {

        Route::middleware(['guest'])
            ->group(function () {
                Route::post('login', 'login');
            });

        Route::middleware(['auth'])
            ->group(function () {
                Route::post('logout', 'logout');

                Route::get('profile', 'profile');
            });
    });
