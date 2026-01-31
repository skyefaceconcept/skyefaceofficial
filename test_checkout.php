<?php

// This is a simple test script to test checkout without UI
require 'vendor/autoload.php';
require 'bootstrap/app.php';

use App\Http\Controllers\CheckoutController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

$app = require_once 'bootstrap/app.php';
$container = $app->make('Illuminate\Contracts\Container\Container');

// Simulated request data
$cartData = json_encode([
    [
        'id' => 1,
        'name' => 'Test Product',
        'licenseDuration' => '1year',
        'totalPrice' => 5000,
        'quantity' => 1,
    ]
]);

$requestData = [
    'customer_name' => 'Test User',
    'customer_email' => 'test@example.com',
    'customer_phone' => '+234 1234567890',
    'address' => '123 Test Street',
    'city' => 'Lagos',
    'state' => 'Lagos',
    'zip' => '100001',
    'country' => 'Nigeria',
    'payment_method' => 'flutterwave',
    'cart' => $cartData,
    'total' => '5000',
];

echo "Testing Checkout.store() with sample data...\n";
echo "Request data: " . json_encode($requestData, JSON_PRETTY_PRINT) . "\n\n";

Log::info('TEST: Checkout test started', ['data' => $requestData]);

echo "✓ Test script ready. Check laravel.log for detailed output.\n";
?>
