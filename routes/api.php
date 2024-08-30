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
        'prefix' => 'payment/{module}/{driver}',
    ],
    function () {
        Route::post('purchase', [PaymentController::class, 'purchase']);
        Route::match(['get', 'post'], 'complete', [PaymentController::class, 'complete']);
    }
);
