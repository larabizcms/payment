<?php

return [
    'methods' => [
        'paypal' => [
            'enabled' => env('PAYMENT_PAYPAL_ENABLED', false),
            'clientId' => env('PAYMENT_PAYPAL_CLIENT_ID'),
            'secret' => env('PAYMENT_PAYPAL_SECRET'),
            'driver' => 'PayPal_Rest',
            'testMode' => env('PAYMENT_PAYPAL_TEST_MODE', false),
            'icon' => 'CreditCardRoundedIcon',
            'label' => 'PayPal / Visa / MasterCard',
            'description' => 'Secure payment via Paypal.',
        ],

        'NganLuong' => [
            'enabled' => env('PAYMENT_NGANLUONG_ENABLED', false),
            'merchantId' => env('PAYMENT_NGANLUONG_MERCHANT_ID'),
            'merchantPassword' => env('PAYMENT_NGANLUONG_MERCHANT_PASSWORD'),
            'receiverEmail' => env('PAYMENT_NGANLUONG_RECEIVER_EMAIL'),
            'sandbox' => env('PAYMENT_NGANLUONG_SANDBOX', true),
            'label' => 'Momo / Bank Transfer (VN)',
        ],

        'Payos' => [
            'enabled' => env('PAYMENT_PAYOS_ENABLED', false),
            'clientId' => env('PAYMENT_PAYOS_CLIENT_ID'),
            'key' => env('PAYMENT_PAYOS_KEY'),
            'checksumKey' => env('PAYMENT_PAYOS_CHECKSUM_KEY'),
            'label' => 'Momo / Zalo Pay / Bank Transfer (VN)',
            'webhook' => \LarabizCMS\Modules\Payment\Methods\Payos\Webhook::class,
        ],
    ],

    'currency_conversion' => [
        'VND' => 26600,
    ],

    'repositories' => [
        \LarabizCMS\Modules\Payment\Repositories\PaymentHistoryRepository::class =>
            \LarabizCMS\Modules\Payment\Repositories\PaymentHistoryRepositoryEloquent::class
    ],
];
