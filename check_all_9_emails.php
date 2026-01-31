<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$emails = \DB::table('mail_logs')
    ->where('to', 'skyefacecon@gmail.com')
    ->orderBy('created_at', 'desc')
    ->limit(25)
    ->get();

echo "\nLatest 25 emails:\n";
echo "═══════════════════════════════════════════════════════════════\n\n";

foreach ($emails as $e) {
    echo "[" . str_pad($e->id, 3) . "] " . substr($e->subject, 0, 70) . "\n";
}

echo "\n═══════════════════════════════════════════════════════════════\n";
echo "Total emails: " . count($emails) . "\n";

// Count by type
$pending = $emails->filter(fn($e) => strpos($e->subject, 'Pending') !== false)->count();
$received = $emails->filter(fn($e) => strpos($e->subject, 'Device Received') !== false)->count();
$diagnosed = $emails->filter(fn($e) => strpos($e->subject, 'Diagnosis') !== false)->count();
$in_progress = $emails->filter(fn($e) => strpos($e->subject, 'Repair In Progress') !== false)->count();
$quality_check = $emails->filter(fn($e) => strpos($e->subject, 'Quality Check In') !== false)->count();
$quality_checked = $emails->filter(fn($e) => strpos($e->subject, 'Quality Checked') !== false)->count();
$cost_approved = $emails->filter(fn($e) => strpos($e->subject, 'Cost Approved') !== false)->count();
$ready = $emails->filter(fn($e) => strpos($e->subject, 'Ready for Pickup') !== false)->count();
$completed = $emails->filter(fn($e) => strpos($e->subject, 'Repair Completed') !== false)->count();

echo "\n📊 Email Type Counts:\n";
echo "  Pending: $pending\n";
echo "  Device Received: $received\n";
echo "  Diagnosis Complete: $diagnosed\n";
echo "  Repair In Progress: $in_progress\n";
echo "  Quality Check In Progress: $quality_check\n";
echo "  Quality Checked: $quality_checked\n";
echo "  Cost Approved: $cost_approved\n";
echo "  Ready for Pickup: $ready\n";
echo "  Repair Completed: $completed\n";
