<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Repair;
use App\Mail\RepairStatusUpdate;
use Illuminate\Support\Facades\Mail;

echo "\n";
echo "╔════════════════════════════════════════════════════════╗\n";
echo "║  REPAIR EMAIL SYSTEM - COMPREHENSIVE TEST              ║\n";
echo "╚════════════════════════════════════════════════════════╝\n\n";

try {
    $repair = Repair::first();
    if (!$repair) {
        echo "ERROR: No repair record found in database\n";
        exit(1);
    }
    
    echo "Test Configuration:\n";
    echo "  Repair ID: " . $repair->id . "\n";
    echo "  Invoice: " . $repair->invoice_number . "\n";
    echo "  Customer: " . $repair->customer_name . "\n";
    echo "  Email: " . $repair->customer_email . "\n";
    echo "  SMTP Host: " . env('MAIL_HOST') . ":" . env('MAIL_PORT') . "\n";
    echo "  Encryption: " . env('MAIL_ENCRYPTION') . "\n\n";
    
    $statuses = [
        'Received',
        'Diagnosed',
        'In Progress',
        'Quality Check',
        'Quality Checked',
        'Ready for Pickup',
        'Completed'
    ];
    
    echo "Testing all repair statuses:\n";
    echo "────────────────────────────────────────────────────────\n";
    
    $success = 0;
    $failed = 0;
    
    foreach ($statuses as $status) {
        try {
            Mail::send(new RepairStatusUpdate($repair, $status, "Automated test - $status"));
            echo "  ✓ $status\n";
            $success++;
        } catch (\Exception $e) {
            echo "  ✗ $status - " . $e->getMessage() . "\n";
            $failed++;
        }
    }
    
    echo "────────────────────────────────────────────────────────\n\n";
    
    // Check logs
    $count = \DB::table('mail_logs')->count();
    $latest = \DB::table('mail_logs')->latest()->limit(10)->get();
    
    echo "Results Summary:\n";
    echo "  Emails Sent: $success / " . count($statuses) . " ✓\n";
    echo "  Errors: $failed\n";
    echo "  Total in mail_logs: $count\n\n";
    
    if ($success === count($statuses)) {
        echo "╔════════════════════════════════════════════════════════╗\n";
        echo "║  ✓ ALL TESTS PASSED - EMAIL SYSTEM WORKING             ║\n";
        echo "╚════════════════════════════════════════════════════════╝\n\n";
        
        echo "Next Steps to Fix Spam Issue:\n";
        echo "  1. Contact your email provider about SPF/DKIM/DMARC\n";
        echo "  2. Add DNS records for email authentication\n";
        echo "  3. Test again after 24-48 hours\n";
        echo "  4. Check REPAIR_EMAIL_SPAM_FIX.md for detailed guide\n\n";
    } else {
        echo "⚠ Some tests failed. Check logs for details.\n\n";
    }
    
} catch (\Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
    echo $e->getTraceAsString() . "\n";
    exit(1);
}
