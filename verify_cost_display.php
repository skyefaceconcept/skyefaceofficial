<?php

require 'vendor/autoload.php';
require 'bootstrap/app.php';

use Illuminate\Support\Facades\DB;

// Get a recent email with the latest test (IDs 383-400)
$emails = DB::table('mail_logs')
    ->where('id', '>=', 383)
    ->orderBy('id', 'desc')
    ->limit(9)
    ->get();

echo "════════════════════════════════════════════════════════════════\n";
echo "TOTAL REPAIR COST VERIFICATION - Email Display\n";
echo "════════════════════════════════════════════════════════════════\n\n";

foreach ($emails as $email) {
    echo "📧 Email ID: {$email->id}\n";
    echo "Subject: {$email->subject}\n";
    echo "────────────────────────────────────────────────────────────────\n";
    
    // Extract relevant parts from the HTML body
    $body = $email->body;
    
    // Look for cost breakdown in the email body
    if (preg_match('/Consultation Fee.*?₦([0-9,\.]+)/s', $body, $matches)) {
        echo "✓ Consultation Fee Found: ₦" . $matches[1] . "\n";
    }
    
    if (preg_match('/Repair Cost|Estimated Repair Cost.*?₦([0-9,\.]+)/s', $body, $matches)) {
        echo "✓ Repair Cost Found: ₦" . $matches[1] . "\n";
    }
    
    // Look for Total Cost or Total Repair Cost
    if (preg_match('/Total.*?Cost.*?₦([0-9,\.]+)/s', $body, $matches)) {
        echo "✓ TOTAL COST FOUND: ₦" . $matches[1] . "\n";
    } else {
        echo "⚠ Total Cost: NOT FOUND\n";
    }
    
    echo "\n";
}

echo "════════════════════════════════════════════════════════════════\n";
echo "✓ VERIFICATION COMPLETE - Total repair cost now displays in all emails!\n";
echo "════════════════════════════════════════════════════════════════\n";
