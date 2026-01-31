<?php

require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Order;
use App\Models\Payment;
use App\Mail\OrderCompletedMail;
use Illuminate\Support\Facades\Mail;

// Get the latest order (Order #5)
$order = Order::where('id', 5)->first();

if (!$order) {
    echo "❌ Order #5 not found\n";
    exit;
}

echo "\n=== SENDING EMAILS FOR ORDER #5 ===\n";
echo "Customer: {$order->customer_email}\n";
echo "Amount: {$order->amount}\n\n";

try {
    // Send order completion email
    echo "1️⃣  Sending OrderCompletedMail...\n";
    Mail::send(new OrderCompletedMail($order));
    echo "   ✓ OrderCompletedMail queued\n\n";
    
    // Send license activation email if license exists
    if ($order->license) {
        echo "2️⃣  Sending LicenseActivationMail...\n";
        $licenseActivationMail = new \App\Mail\LicenseActivationMail($order->license);
        Mail::send($licenseActivationMail);
        echo "   ✓ LicenseActivationMail queued\n\n";
    } else {
        echo "⚠️  No license found for this order yet\n\n";
    }
    
    // Get payment info for payment confirmation
    $payment = Payment::where('order_id', $order->id)->first();
    if ($payment) {
        echo "3️⃣  Sending Payment Confirmation Email...\n";
        
        // Send customer notification
        Mail::send(
            'emails.payments.completed',
            [
                'customerName' => $order->customer_name,
                'customerEmail' => $order->customer_email,
                'amount' => $order->amount,
                'currency' => 'NGN',
                'reference' => $payment->reference,
                'transactionId' => $payment->flutterwave_id ?? $payment->paystack_reference ?? $payment->id,
                'invoiceNumber' => $order->invoice_number,
                'payment' => $payment,
                'quote' => null,
            ],
            function($message) use ($order) {
                $message->to($order->customer_email)
                    ->subject('Payment Received - ' . config('app.name'));
            }
        );
        echo "   ✓ Customer payment confirmation queued\n";
        
        // Send admin notification
        Mail::send(
            'emails.payments.admin-notification',
            [
                'customerName' => $order->customer_name,
                'customerEmail' => $order->customer_email,
                'amount' => $order->amount,
                'currency' => 'NGN',
                'reference' => $payment->reference,
                'transactionId' => $payment->flutterwave_id ?? $payment->paystack_reference ?? $payment->id,
                'invoiceNumber' => $order->invoice_number,
                'payment' => $payment,
                'quote' => (object)['id' => $order->id, 'package' => 'Software License', 'status' => 'completed', 'name' => $order->customer_name],
            ],
            function($message) {
                $message->to('info@skyeface.com')
                    ->subject('New Payment Received - ' . config('app.name'));
            }
        );
        echo "   ✓ Admin payment notification queued\n\n";
    }
    
    echo "✅ All emails queued successfully!\n";
    echo "📧 Run: php artisan queue:work to process them\n\n";
    
} catch (\Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo $e->getTraceAsString() . "\n";
}
