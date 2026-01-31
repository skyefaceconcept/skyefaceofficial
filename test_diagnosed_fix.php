<?php

require 'vendor/autoload.php';
require 'bootstrap/app.php';

use App\Models\Repair;
use App\Mail\RepairStatusUpdate;
use Illuminate\Support\Facades\Mail;

// Get the test repair
$repair = Repair::find(8);

if (!$repair) {
    echo "Repair not found!\n";
    exit(1);
}

echo "════════════════════════════════════════════════════════════════\n";
echo "Testing: Diagnosis Complete (Fixed)\n";
echo "════════════════════════════════════════════════════════════════\n\n";

try {
    // Update repair to Diagnosed status
    $repair->update(['repair_status' => 'Diagnosed']);
    
    // Send the email
    Mail::send(new RepairStatusUpdate($repair));
    
    echo "✓ Email sent successfully!\n";
    echo "Status: Diagnosed\n";
    echo "Invoice: {$repair->invoice_number}\n";
    echo "Email: {$repair->customer_email}\n";
    echo "\n✓ Check your inbox in 5-10 minutes\n";
    echo "✓ Verify that 'Diagnosis Complete' email is now in INBOX (not spam)\n";
    
} catch (\Exception $e) {
    echo "✗ Error: " . $e->getMessage() . "\n";
}
