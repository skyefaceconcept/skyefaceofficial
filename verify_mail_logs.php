<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\MailLog;
use Illuminate\Support\Facades\Mail;
use App\Mail\WelcomeMail;
use Illuminate\Support\Facades\Log;

try {
    echo "Sending test email...\n";

    // Create a test user
    $testUser = new stdClass();
    $testUser->name = 'John Doe';
    $testUser->email = 'info@skyeface.com.ng';

    Mail::to($testUser->email)->send(new WelcomeMail($testUser));

    echo "Email sent!\n\n";
    sleep(1); // Give time for event to process

    $logs = MailLog::all();
    echo "Total mail logs: " . $logs->count() . "\n\n";

    foreach ($logs as $log) {
        echo "- To: " . $log->to . "\n";
        echo "  Subject: " . $log->subject . "\n";
        echo "  Created: " . $log->created_at . "\n";
        echo "  Has body: " . (!empty($log->body) ? 'Yes (' . strlen($log->body) . ' bytes)' : 'No') . "\n\n";
    }
} catch (\Throwable $e) {
    echo "Error: " . $e->getMessage() . "\n";
    Log::error('Verification error', ['error' => $e->getMessage()]);
}
?>
