<?php
// Script to check payment statistics - run via: php artisan tinker < check-payments.php

use App\Models\Payment;

echo "\n=== PAYMENT TRACKING STATISTICS ===\n";
echo "Total Payments: " . Payment::count() . "\n";
echo "Quote Payments: " . Payment::whereNotNull('quote_id')->count() . "\n";
echo "Repair Payments: " . Payment::whereNotNull('repair_id')->count() . "\n";
echo "Direct Payments: " . Payment::whereNull('quote_id')->whereNull('repair_id')->count() . "\n\n";

// Check by status
echo "=== PAYMENTS BY STATUS ===\n";
echo "Completed: " . Payment::where('status', 'completed')->count() . "\n";
echo "Pending: " . Payment::where('status', 'pending')->count() . "\n";
echo "Failed: " . Payment::where('status', 'failed')->count() . "\n";
echo "Cancelled: " . Payment::where('status', 'cancelled')->count() . "\n\n";

// Check by processor
echo "=== PAYMENTS BY PROCESSOR ===\n";
echo "Flutterwave: " . Payment::where('payment_source', 'like', '%Flutterwave%')->count() . "\n";
echo "Paystack: " . Payment::where('payment_source', 'like', '%Paystack%')->count() . "\n\n";

// Calculate total revenue
echo "=== REVENUE STATISTICS ===\n";
$totalRevenue = Payment::where('status', 'completed')->sum('amount');
echo "Total Revenue (Completed): ₦" . number_format($totalRevenue, 2) . "\n";
echo "Total Amount Pending: ₦" . number_format(Payment::where('status', 'pending')->sum('amount'), 2) . "\n\n";

// Show latest 10 payments
echo "=== LATEST 10 PAYMENTS ===\n";
echo str_pad("ID", 5) . " | " . str_pad("Type", 10) . " | " . str_pad("Amount", 12) . " | " . str_pad("Status", 12) . " | " . str_pad("Processor", 12) . " | " . str_pad("Date", 19) . "\n";
echo str_repeat("-", 90) . "\n";

$payments = Payment::with(['quote', 'repair'])->latest()->limit(10)->get();
foreach ($payments as $p) {
    $type = $p->quote ? 'Quote' : ($p->repair ? 'Repair' : 'Direct');
    echo str_pad($p->id, 5) . " | " . 
         str_pad($type, 10) . " | " . 
         str_pad("₦" . number_format($p->amount, 2), 12) . " | " . 
         str_pad($p->status, 12) . " | " . 
         str_pad(substr($p->payment_source, 0, 12), 12) . " | " . 
         $p->created_at->format('Y-m-d H:i:s') . "\n";
}

echo "\n=== REPAIR PAYMENT DETAILS ===\n";
$repairPayments = Payment::whereNotNull('repair_id')->with('repair')->latest()->limit(5)->get();
if ($repairPayments->count() > 0) {
    foreach ($repairPayments as $p) {
        echo "Payment #{$p->id} | Repair: {$p->repair->invoice_number} | ₦{$p->amount} | {$p->status} | {$p->payment_source}\n";
    }
} else {
    echo "No repair payments found.\n";
}

echo "\n=== QUOTE PAYMENT DETAILS ===\n";
$quotePayments = Payment::whereNotNull('quote_id')->with('quote')->latest()->limit(5)->get();
if ($quotePayments->count() > 0) {
    foreach ($quotePayments as $p) {
        echo "Payment #{$p->id} | Quote: #{$p->quote->id} | ₦{$p->amount} | {$p->status} | {$p->payment_source}\n";
    }
} else {
    echo "No quote payments found.\n";
}

echo "\n";
