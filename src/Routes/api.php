<?php

use LarabizCMS\Modules\Payment\Http\Controllers\MethodController;
use LarabizCMS\Modules\Payment\Http\Controllers\PaymentController;
use LarabizCMS\Modules\Payment\Http\Controllers\APIs\PaymentHistoryController;
use LarabizCMS\Modules\Payment\Http\Controllers\Admin;

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
        'middleware' => [
            ...config('larabiz.auth_middleware'),
        ],
    ],
    function () {
        Route::post('{module}/purchase', [PaymentController::class, 'purchase']);
        Route::get('{module}/histories', [PaymentHistoryController::class, 'index']);
    }
);

Route::group(
    [
        'prefix' => 'payment',
    ],
    function () {
        Route::post('{module}/guest-purchase', [PaymentController::class, 'guestPurchase'])
            ->middleware([\LarabizCMS\Core\Http\Middleware\Captcha::class]);
        Route::post('{module}/complete/{transactionId}', [PaymentController::class, 'complete']);
        Route::post('{module}/cancel/{transactionId}', [PaymentController::class, 'cancel']);
        Route::post('webhook/{method}', [PaymentController::class, 'webhook']);
        Route::get('methods', [MethodController::class, 'index']);
    }
);

Route::group(
    [
        'prefix' => 'admin/pages/payment',
    ],
    function () {
        Route::get('{module}/histories', [Admin\PaymentHistoryController::class, 'index']);
    }
);
