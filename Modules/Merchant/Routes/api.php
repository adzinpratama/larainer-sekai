<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Modules\Merchant\Http\Controllers\MerchantController;
use Modules\Merchant\Http\Controllers\MerchantUserController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::prefix('v1')
    ->middleware(['auth'])
    ->group(function () {

        Route::resource('merchant', MerchantController::class)
            ->only(['index', 'store', 'destroy']);

        Route::resource('merchant/user', MerchantUserController::class)
            ->only(['index', 'store', 'destroy']);
    });
