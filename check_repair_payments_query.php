<?php
require 'vendor/autoload.php';
require 'bootstrap/app.php';

use App\Models\Payment;
use App\Models\Repair;

echo "=== REPAIR PAYMENT DIAGNOSTICS ===\n\n";

// Check raw database repairs
echo "1. REPAIRS WITH PAYMENT REFERENCE:\n";
$repairs = Repair::whereNotNull('payment_reference')
    ->get(['id', 'invoice_number', 'payment_reference', 'payment_status', 'customer_email']);

foreach ($repairs as $repair) {
    echo "  Repair #{$repair->id}: {$repair->invoice_number}\n";
    echo "    Reference: {$repair->payment_reference}\n";
    echo "    Status: {$repair->payment_status}\n";
    echo "    Email: {$repair->customer_email}\n";
}

echo "\n2. PAYMENTS TABLE - ALL RECORDS:\n";
$payments = Payment::all(['id', 'quote_id', 'repair_id', 'reference', 'transaction_id', 'status', 'payment_source', 'created_at']);
echo "  Total records: " . count($payments) . "\n";

foreach ($payments as $payment) {
    echo "  Payment #{$payment->id}:\n";
    echo "    Quote ID: " . ($payment->quote_id ?? 'NULL') . "\n";
    echo "    Repair ID: " . ($payment->repair_id ?? 'NULL') . "\n";
    echo "    Reference: {$payment->reference}\n";
    echo "    Transaction ID: " . ($payment->transaction_id ?? 'NULL') . "\n";
    echo "    Status: {$payment->status}\n";
    echo "    Source: {$payment->payment_source}\n";
    echo "    Created: {$payment->created_at}\n";
}

echo "\n3. PAYMENTS TABLE - REPAIR PAYMENTS ONLY:\n";
$repairPayments = Payment::whereNotNull('repair_id')->get(['id', 'repair_id', 'reference', 'status']);
echo "  Count: " . count($repairPayments) . "\n";
foreach ($repairPayments as $p) {
    echo "  Payment #{$p->id}: Repair #{$p->repair_id}, Ref: {$p->reference}, Status: {$p->status}\n";
}

echo "\n4. QUERY SIMULATION (adminList equivalent):\n";
$query = Payment::with(['quote', 'repair'])
    ->orderBy('created_at', 'desc');
$simulated = $query->get();
echo "  Query returned: " . count($simulated) . " records\n";

foreach ($simulated as $payment) {
    echo "  Payment #{$payment->id}:\n";
    echo "    Quote: " . ($payment->quote ? "Quote #{$payment->quote->id}" : "NULL") . "\n";
    echo "    Repair: " . ($payment->repair ? "Repair #{$payment->repair->id}" : "NULL") . "\n";
}

echo "\n5. SEARCH FOR MATCHING REPAIRS BY PAYMENT_REFERENCE:\n";
$repairs_with_ref = Repair::whereNotNull('payment_reference')->get();
foreach ($repairs_with_ref as $repair) {
    $found = Payment::where('reference', $repair->payment_reference)->orWhere('transaction_id', $repair->payment_reference)->first();
    echo "  Repair #{$repair->id} ({$repair->payment_reference}): ";
    echo $found ? "FOUND in Payment table (ID: {$found->id})" : "NOT FOUND";
    echo "\n";
}

echo "\n=== END DIAGNOSTICS ===\n";
?>
