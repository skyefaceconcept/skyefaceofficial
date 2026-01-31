<?php

/**
 * Quote System - Quick Diagnostic Test
 * Run this file to test the quote system
 */

$baseUrl = 'http://127.0.0.1:8000';

echo "======================================\n";
echo "QUOTE SYSTEM DIAGNOSTIC TEST\n";
echo "======================================\n\n";

// Test 1: Check if /services page loads
echo "[Test 1] Checking /services page...\n";
$response = file_get_contents($baseUrl . '/services');
if (strpos($response, 'quote-modal') !== false) {
    echo "✓ Quote modal found on page\n";
} else {
    echo "✗ Quote modal NOT found on page\n";
}

if (strpos($response, 'open-quote-btn') !== false) {
    echo "✓ Quote buttons found on page\n";
} else {
    echo "✗ Quote buttons NOT found on page\n";
}

if (strpos($response, 'submitQuoteForm') !== false) {
    echo "✓ submitQuoteForm function found\n";
} else {
    echo "✗ submitQuoteForm function NOT found\n";
}

echo "\n[Test 2] Checking quote routes...\n";
// We can't directly test routes, but we can verify the controller exists
if (file_exists(__DIR__ . '/app/Http/Controllers/QuoteController.php')) {
    echo "✓ QuoteController exists\n";
} else {
    echo "✗ QuoteController NOT found\n";
}

echo "\n[Test 3] Check if Quote model exists...\n";
if (file_exists(__DIR__ . '/app/Models/Quote.php')) {
    echo "✓ Quote model exists\n";
} else {
    echo "✗ Quote model NOT found\n";
}

echo "\n[Test 4] Testing quote submission via API...\n";
$quoteData = [
    'package' => 'Test Package',
    'name' => 'Test User',
    'email' => 'test@example.com',
    'phone' => '1234567890',
    'details' => 'This is a test quote submission with sufficient details',
];

$ch = curl_init($baseUrl . '/quotes');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($quoteData));
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json',
    'X-CSRF-TOKEN: test'
]);
curl_setopt($ch, CURLOPT_TIMEOUT, 5);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($httpCode == 200 || $httpCode == 422 || $httpCode == 429) {
    echo "✓ Quote endpoint responds (HTTP $httpCode)\n";
    if ($response) {
        $data = json_decode($response, true);
        if (isset($data['quote_id'])) {
            echo "✓ Quote created successfully (ID: {$data['quote_id']})\n";
        } elseif (isset($data['errors'])) {
            echo "✓ Validation errors received (expected)\n";
        }
    }
} else {
    echo "✗ Quote endpoint error (HTTP $httpCode)\n";
}

echo "\n======================================\n";
echo "DIAGNOSTIC COMPLETE\n";
echo "======================================\n";

?>
