<?php

use App\Http\Controllers\Api\ExchangeRateController;
use Illuminate\Support\Facades\Route;

//use Illuminate\Http\Request;

//Route::get('/user', function (Request $request) {
//    return $request->user();
//})->middleware('auth:sanctum');

Route::controller(ExchangeRateController::class)
    ->prefix('v1')
    ->group(function () {
        Route::get('exchange-rates', 'getRates');
        Route::post('exchange-rates', 'storeRate');
    });
