<?php
require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

// Check repair 4,5,6
for ($id = 4; $id <= 6; $id++) {
    echo "\n=== Checking Repair $id ===\n";
    $repair = \App\Models\Repair::find($id);
    if ($repair) {
        echo "Found: ID={$repair->id}, Invoice={$repair->invoice_number}, PaymentRef={$repair->payment_reference}, PaymentStatus={$repair->payment_status}\n";
    } else {
        echo "Not found\n";
    }
}

// Try to find repair by payment reference
echo "\n=== Looking for REPAIR-6-1769034639 ===\n";
$repairByRef = \App\Models\Repair::where('payment_reference', 'REPAIR-6-1769034639')->first();
if ($repairByRef) {
    echo "Found: ID={$repairByRef->id}\n";
} else {
    echo "Not found\n";
}
