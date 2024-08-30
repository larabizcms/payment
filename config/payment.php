<?php

return [
    'methods' => [
        'PayPal_Rest' => [
            'clientId' => env('PAYMENT_PAYPAL_CLIENT_ID'),
            'secret'   => env('PAYMENT_PAYPAL_SECRET'),
            'testMode' => env('PAYMENT_PAYPAL_TEST_MODE', false),
        ],
    ],
];
