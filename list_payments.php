<?php

require 'bootstrap/app.php';

$app = require_once 'bootstrap/app.php';
$kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Payment;

echo "=== CURRENT PAYMENTS IN DATABASE ===\n\n";

$payments = Payment::select('id', 'reference', 'customer_name', 'customer_email', 'amount', 'currency', 'status', 'payment_method', 'created_at')->orderBy('created_at', 'desc')->get();

if ($payments->count() > 0) {
    printf("%-4s | %-30s | %-25s | %-30s | %-12s | %-10s | %-12s | %-20s | %s\n", 'ID', 'Reference', 'Customer Name', 'Customer Email', 'Amount', 'Currency', 'Status', 'Payment Method', 'Created At');
    echo str_repeat('-', 200) . "\n";

    foreach ($payments as $payment) {
        printf("%-4d | %-30s | %-25s | %-30s | %-12.2f | %-10s | %-12s | %-20s | %s\n",
            $payment->id,
            $payment->reference,
            substr($payment->customer_name, 0, 25),
            substr($payment->customer_email, 0, 30),
            $payment->amount,
            $payment->currency,
            $payment->status,
            $payment->payment_method ?? 'N/A',
            $payment->created_at->format('Y-m-d H:i:s')
        );
    }
    echo "\nTotal Payments: " . $payments->count() . "\n";
} else {
    echo "No payments found in the database.\n";
}
