<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ExchangeRateController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')
    ->group(function () {
        Route::post('/register', [AuthController::class, 'register']);
        Route::post('/login', [AuthController::class, 'login']);

        Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');

        Route::controller(ExchangeRateController::class)
            ->group(function () {
                Route::get('exchange-rates', 'getRates');
                Route::post('exchange-rates', 'storeRate');
            });
    });
