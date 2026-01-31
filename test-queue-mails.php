<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Order;
use App\Mail\OrderCompletedMail;
use App\Mail\LicenseActivationMail;
use Illuminate\Support\Facades\Mail;

$order = Order::find(4);
if ($order) {
    $license = $order->license;
    if ($license) {
        Mail::queue(new OrderCompletedMail($order));
        Mail::queue(new LicenseActivationMail($license));
        echo "✓ Queued OrderCompletedMail and LicenseActivationMail for order 4\n";
    } else {
        echo "✗ No license for order 4\n";
    }
} else {
    echo "✗ Order 4 not found\n";
}
