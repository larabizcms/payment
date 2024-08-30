<?php

use LarabizCMS\Modules\Payment\Http\Controllers\PaymentController;
use LarabizCMS\Modules\Payment\Http\Controllers\PaymentHistoryController;

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

Route::group(
    [
        'prefix' => 'payment',
        'middleware' => ['auth:api'],
    ],
    function () {
        Route::post('{module}/purchase', [PaymentController::class, 'purchase']);
        Route::post('{module}/complete/{transactionId}', [PaymentController::class, 'complete']);
        Route::post('{module}/cancel/{transactionId}', [PaymentController::class, 'purchase']);

        Route::resource('{module}/histories', PaymentHistoryController::class)
            ->only(['index']);
    }
);
