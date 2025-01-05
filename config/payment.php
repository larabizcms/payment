<?php

return [
    'methods' => [
        'paypal' => [
            'enabled'  => env('PAYMENT_PAYPAL_ENABLED', false),
            'clientId' => env('PAYMENT_PAYPAL_CLIENT_ID'),
            'secret'   => env('PAYMENT_PAYPAL_SECRET'),
            'driver' => 'PayPal_Rest',
            'testMode' => env('PAYMENT_PAYPAL_TEST_MODE', false),
            'icon'     => 'CreditCardRoundedIcon',
            'label'     => 'PayPal / Visa / MasterCard',
            'description' => 'Secure payment via Paypal.',
        ],

        'NganLuong' => [
            'enabled'  => env('PAYMENT_NGANLUONG_ENABLED', false),
            'merchantId' => env('PAYMENT_NGANLUONG_MERCHANT_ID'),
            'merchantPassword' => env('PAYMENT_NGANLUONG_MERCHANT_PASSWORD'),
            'receiverEmail' => env('PAYMENT_NGANLUONG_RECEIVER_EMAIL'),
            'sandbox' => env('PAYMENT_NGANLUONG_SANDBOX', true),
        ],
    ],
];
