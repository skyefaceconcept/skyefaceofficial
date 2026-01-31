<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Repair;
use App\Mail\RepairStatusUpdate;
use Illuminate\Support\Facades\Mail;

$repair = Repair::first();

$statuses = ['Received', 'Diagnosed', 'In Progress', 'Quality Check', 'Quality Checked', 'Ready for Pickup', 'Completed'];

echo "Testing all repair statuses:\n";
foreach ($statuses as $status) {
    try {
        Mail::send(new RepairStatusUpdate($repair, $status, "Test"));
        echo "  ✓ $status\n";
    } catch (\Exception $e) {
        echo "  ✗ $status - " . $e->getMessage() . "\n";
    }
}

$count = \DB::table('mail_logs')->count();
echo "\nTotal emails logged: $count\n";
echo "✓ All tests completed!\n";
