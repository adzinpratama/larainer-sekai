<?php

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

use Illuminate\Support\Facades\Route;
use Modules\Merchant\Http\Controllers\MerchantController;

Route::prefix('merchant')
    ->middleware(['auth:web'])
    ->as('merchant.')
    ->group(function () {
        Route::resource('content', MerchantController::class)
            ->only(['index', 'store', 'destroy']);
    });
