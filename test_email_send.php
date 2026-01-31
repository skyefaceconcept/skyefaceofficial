<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Quote;
use App\Mail\NewQuoteNotification;
use Illuminate\Support\Facades\Mail;

$quote = Quote::find(5);

if (!$quote) {
    echo "Quote not found\n";
    exit;
}

try {
    echo "About to send email...\n";
    Mail::to('test@skyeface.com.ng')->send(new NewQuoteNotification($quote));
    echo "✓ Email sent successfully\n";

    // Check if logged
    $count = \DB::table('mail_logs')->count();
    echo "Mail logs count: " . $count . "\n";

} catch (\Exception $e) {
    echo "✗ Error: " . $e->getMessage() . "\n";
    echo "Trace: " . $e->getTraceAsString() . "\n";
}
