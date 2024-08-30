<?php

use LarabizCMS\Modules\Payment\Http\Controllers\PaymentController;

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
    ],
    function () {
        Route::post('{module}/purchase', [PaymentController::class, 'purchase'])
            ->name('api.payment.purchase');
        Route::match(['get', 'post'], '{module}/complete/{transactionId}', [PaymentController::class, 'complete'])
            ->name('api.payment.complete');
        Route::get('{module}/cancel/{transactionId}', [PaymentController::class, 'purchase'])
            ->name('api.payment.cancel');
    }
);
