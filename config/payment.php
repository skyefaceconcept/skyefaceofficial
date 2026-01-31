<?php

return [
    /**
     * Active payment processor
     * Options: 'flutterwave', 'paystack'
     */
    'active_processor' => env('PAYMENT_ACTIVE_PROCESSOR', 'flutterwave'),

    /**
     * Flutterwave Configuration
     */
    'flutterwave' => [
        'public_key' => env('FLUTTERWAVE_PUBLIC_KEY'),
        'secret_key' => env('FLUTTERWAVE_SECRET_KEY'),
        'encrypt_key' => env('FLUTTERWAVE_ENCRYPT_KEY'),
        'environment' => env('FLUTTERWAVE_ENVIRONMENT', 'live'), // live or sandbox
        'enabled' => env('FLUTTERWAVE_ENABLED', false),
        'base_url' => env('FLUTTERWAVE_ENVIRONMENT', 'live') === 'sandbox'
            ? 'https://staging-api.flutterwave.com'
            : 'https://api.flutterwave.com',
    ],

    /**
     * Paystack Configuration
     */
    'paystack' => [
        'public_key' => env('PAYSTACK_PUBLIC_KEY'),
        'secret_key' => env('PAYSTACK_SECRET_KEY'),
        'environment' => env('PAYSTACK_ENVIRONMENT', 'live'), // live or test
        'currency' => env('PAYSTACK_CURRENCY', 'NGN'),
        'enabled' => env('PAYSTACK_ENABLED', false),
        'base_url' => env('PAYSTACK_ENVIRONMENT', 'live') === 'test'
            ? 'https://api.paystack.co'
            : 'https://api.paystack.co',
    ],

    /**
     * Global Payment Settings
     */
    'currency' => env('PAYMENT_CURRENCY', 'NGN'),
    'timeout' => env('PAYMENT_TIMEOUT', 30), // minutes
    'webhook_secret' => env('PAYMENT_WEBHOOK_SECRET'),
    'test_mode' => env('PAYMENT_TEST_MODE', false),

    /**
     * Payment Status Values
     */
    'statuses' => [
        'pending' => 'Pending',
        'completed' => 'Completed',
        'failed' => 'Failed',
        'cancelled' => 'Cancelled',
        'refunded' => 'Refunded',
    ],

    /**
     * Currency Symbols
     */
    'currency_symbols' => [
        'NGN' => '₦',
        'USD' => '$',
        'GBP' => '£',
        'EUR' => '€',
        'GHS' => 'GH₵',
        'KES' => 'KSh',
        'ZAR' => 'R',
    ],
];
