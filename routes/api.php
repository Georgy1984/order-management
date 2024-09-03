<?php

use App\Http\Controllers\Api\OrderController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;



Route::prefix('orders')->group(function () {

    Route::post('/create', [OrderController::class, 'create']);
    Route::post('/pay/{order}', [OrderController::class, 'pay']);
    Route::post('/callback/{order}', [OrderController::class, 'paymentCallback']);
});
