<?php
// Load Laravel environment
require __DIR__ . '/bootstrap/app.php';

use Illuminate\Support\Facades\Mail;
use Illuminate\Mail\Message;

try {
    echo "Testing SMTP connection...\n";
    echo "SMTP Host: " . env('MAIL_HOST') . "\n";
    echo "SMTP Port: " . env('MAIL_PORT') . "\n";
    echo "SMTP Encryption: " . env('MAIL_ENCRYPTION') . "\n";
    echo "From: " . config('mail.from.address') . "\n\n";

    // Try sending a simple email
    Mail::raw('This is a test email.', function ($message) {
        $message->to('skyefacecon@gmail.com')
                ->subject('SMTP Test from Skyeface');
    });

    echo "✓ Email sent successfully!\n";
} catch (Exception $e) {
    echo "✗ Error: " . $e->getMessage() . "\n";
    echo "Class: " . get_class($e) . "\n";
    if (method_exists($e, 'getTraceAsString')) {
        echo "\nTrace:\n" . $e->getTraceAsString() . "\n";
    }
}
