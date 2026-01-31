<?php

require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Services\InvoiceNumberService;
use App\Models\Order;

echo "\n=== INVOICE NUMBER GENERATION TEST ===\n\n";

// Test generating a few invoice numbers
echo "Generating 3 test invoice numbers:\n";
for ($i = 0; $i < 3; $i++) {
    $invoiceNumber = InvoiceNumberService::generate();
    echo "  Invoice #" . ($i + 1) . ": " . $invoiceNumber . "\n";
}

echo "\n=== EXISTING ORDERS WITH INVOICE NUMBERS ===\n";
$orders = Order::whereNotNull('invoice_number')->latest()->limit(5)->get();

if (count($orders) > 0) {
    echo "\nFound " . count($orders) . " orders with invoice numbers:\n\n";
    foreach ($orders as $order) {
        echo "Order #" . $order->id . ":\n";
        echo "  Invoice: " . $order->invoice_number . "\n";
        echo "  Customer: " . $order->customer_email . "\n";
        echo "  Amount: ₦" . number_format($order->amount, 2) . "\n";
        echo "  Status: " . $order->status . "\n";
        echo "  Created: " . $order->created_at->format('Y-m-d H:i:s') . "\n\n";
    }
} else {
    echo "\n❌ No orders with invoice numbers found yet.\n";
    echo "New orders will automatically get invoice numbers.\n\n";
}

// Show next invoice number
echo "Next Invoice Number (preview): " . InvoiceNumberService::getNext() . "\n\n";
