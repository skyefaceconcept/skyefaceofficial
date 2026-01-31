<?php

// Load Laravel
require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Repair;
use App\Mail\RepairStatusUpdate;
use Illuminate\Support\Facades\Mail;

try {
    echo "Testing Repair Status Update Email\n";
    echo "===================================\n\n";
    
    $repair = Repair::first();
    if (!$repair) {
        echo "No repair found\n";
        exit(1);
    }
    
    echo "Repair ID: " . $repair->id . "\n";
    echo "Invoice: " . $repair->invoice_number . "\n";
    echo "Customer Email: " . $repair->customer_email . "\n\n";
    
    echo "Sending status update email...\n";
    Mail::send(new RepairStatusUpdate($repair, "In Progress", "Testing from PHP script"));
    
    echo "✓ Email sent successfully!\n";
    
    // Check if it was logged
    sleep(1);
    $logs = \DB::table('mail_logs')->latest()->first();
    if ($logs) {
        echo "\n✓ Email logged to database:\n";
        echo "ID: " . $logs->id . "\n";
        echo "To: " . $logs->to . "\n";
        echo "Subject: " . $logs->subject . "\n";
    } else {
        echo "\n✗ Email NOT found in mail_logs table\n";
    }
    
} catch (\Exception $e) {
    echo "✗ Error: " . $e->getMessage() . "\n";
    echo "\nFull trace:\n" . $e->getTraceAsString() . "\n";
    exit(1);
}
