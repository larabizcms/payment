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
    ],
];
