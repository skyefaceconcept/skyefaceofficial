<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Repair;
use App\Mail\RepairStatusUpdate;
use Illuminate\Support\Facades\Mail;

try {
    echo "\n==========================================\n";
    echo "Testing All Repair Status Update Emails\n";
    echo "==========================================\n\n";
    
    $repair = Repair::first();
    if (!$repair) {
        echo "No repair found\n";
        exit(1);
    }
    
    $statuses = ['Received', 'Diagnosed', 'In Progress', 'Quality Check', 'Quality Checked', 'Ready for Pickup', 'Completed'];
    $success_count = 0;
    $fail_count = 0;
    
    foreach ($statuses as $status) {
        try {
            echo "Testing: $status... ";
            Mail::send(new RepairStatusUpdate($repair, $status, "Test: $status status"));
            echo "✓ SENT\n";
            $success_count++;
        } catch (\Exception $e) {
            echo "✗ FAILED: " . $e->getMessage() . "\n";
            $fail_count++;
        }
    }
    
    echo "\n==========================================\n";
    echo "Summary: $success_count sent, $fail_count failed\n";
    echo "==========================================\n";
    
    // Check database logs
    $logs = \DB::table('mail_logs')->latest()->limit(7)->get();
    echo "\nLatest email logs in database:\n";
    foreach ($logs as $log) {
        echo "  - {$log->subject}\n";
    }
    
} catch (\Exception $e) {
    echo "✗ Error: " . $e->getMessage() . "\n";
    exit(1);
}
