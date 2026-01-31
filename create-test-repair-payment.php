<?php
// Script to create test repair payment records

use App\Models\Payment;
use App\Models\Repair;

echo "\n=== CREATING TEST REPAIR PAYMENTS ===\n\n";

// Get the latest repair
$repair = Repair::latest()->first();

if (!$repair) {
    echo "No repairs found in database. Please create a repair booking first.\n\n";
    return;
}

echo "Found Repair: #{$repair->id} | Invoice: {$repair->invoice_number}\n";
echo "Customer: {$repair->customer_name} ({$repair->customer_email})\n";
echo "Cost Estimate: {$repair->cost_estimate}\n\n";

// Create a test payment record for this repair
echo "Creating test payment record...\n";

$payment = Payment::create([
    'repair_id' => $repair->id,
    'amount' => $repair->cost_estimate,
    'currency' => 'NGN',
    'status' => 'completed',
    'transaction_id' => 'TEST-REPAIR-' . time(),
    'reference' => 'REPAIR-' . $repair->id . '-' . time(),
    'customer_email' => $repair->customer_email,
    'customer_name' => $repair->customer_name,
    'payment_method' => 'card',
    'payment_source' => 'Paystack',
    'paid_at' => now(),
    'response_data' => json_encode([
        'type' => 'test',
        'repair_id' => $repair->id,
        'created_via' => 'tinker_test'
    ]),
]);

echo "Payment Created!\n\n";
echo "Payment Details:\n";
echo "- ID: {$payment->id}\n";
echo "- Reference: {$payment->reference}\n";
echo "- Transaction ID: {$payment->transaction_id}\n";
echo "- Amount: {$payment->amount}\n";
echo "- Status: {$payment->status}\n";
echo "- Processor: {$payment->payment_source}\n";
echo "- Repair ID: {$payment->repair_id}\n";
echo "- Paid At: {$payment->paid_at}\n\n";

// Show updated statistics
echo "=== UPDATED STATISTICS ===\n";
echo "Total Payments: " . Payment::count() . "\n";
echo "Quote Payments: " . Payment::whereNotNull('quote_id')->count() . "\n";
echo "Repair Payments: " . Payment::whereNotNull('repair_id')->count() . "\n";
echo "Total Revenue: " . Payment::where('status', 'completed')->sum('amount') . "\n\n";

// Show all repair payments
echo "=== ALL REPAIR PAYMENTS ===\n";
$repairPayments = Payment::whereNotNull('repair_id')->with('repair')->get();
echo "Total Repair Payments: {$repairPayments->count()}\n\n";
foreach ($repairPayments as $p) {
    echo "Payment #{$p->id} | Repair: {$p->repair->invoice_number} | {$p->amount} | {$p->status}\n";
}

echo "\nTEST PAYMENT CREATED AND TRACKED IN ADMIN/PAYMENTS\n";
echo "You can now see this payment in the admin dashboard at /admin/payments\n\n";

