<?php
require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$repairsWithPayments = \App\Models\Repair::where('payment_status', 'completed')->count();
$paymentsTotal = \App\Models\Payment::count();
$paymentsWithRepair = \App\Models\Payment::whereNotNull('repair_id')->count();
$paymentsWithQuote = \App\Models\Payment::whereNotNull('quote_id')->count();

echo "=== Payment Status ===\n";
echo "Repairs with completed payments: $repairsWithPayments\n";
echo "Total payments in payment table: $paymentsTotal\n";
echo "Payments with repair_id set: $paymentsWithRepair\n";
echo "Payments with quote_id set: $paymentsWithQuote\n";

echo "\n=== Sample Repair Payment ===\n";
$repair = \App\Models\Repair::where('payment_status', 'completed')->first();
if ($repair) {
    echo "Repair ID: {$repair->id}\n";
    echo "Invoice: {$repair->invoice_number}\n";
    echo "Payment Reference: {$repair->payment_reference}\n";
    echo "Payment Status: {$repair->payment_status}\n";
    echo "Cost Actual: {$repair->cost_actual}\n";
    
    $payment = \App\Models\Payment::where('repair_id', $repair->id)->first();
    if ($payment) {
        echo "Payment Record Found: Yes\n";
    } else {
        echo "Payment Record Found: No - THIS IS THE ISSUE!\n";
    }
}
