<?php
use App\Models\Payment;
use App\Models\Repair;

echo "\n=== RECOVERING 60000 FLUTTERWAVE PAYMENT ===\n\n";

// The payment from logs:
// Reference: REPAIR-23-1769294744
// Transaction ID: 9966043
// Amount: 60000
// Status: successful
// Email: ravesb_64b3ab6ca8f152fbe22b_skyefaceconcept@gmail.com
// Repair ID: 23

$repair = Repair::find(23);

if ($repair) {
    echo "✅ Found Repair #23\n";
    echo "Repair Reference: " . ($repair->reference ?? 'N/A') . "\n";
    echo "Repair Cost: ₦" . ($repair->cost_actual ?? 'N/A') . "\n\n";
    
    // Check if payment already exists
    $existing = Payment::where('transaction_id', '9966043')->first();
    
    if ($existing) {
        echo "❌ Payment already exists (ID: " . $existing->id . ")\n";
    } else {
        // Create the payment record
        $payment = Payment::create([
            'repair_id' => 23,
            'transaction_id' => '9966043',
            'amount' => 60000,
            'currency' => 'NGN',
            'status' => 'completed',
            'reference' => 'REPAIR-23-1769294744',
            'customer_email' => 'ravesb_64b3ab6ca8f152fbe22b_skyefaceconcept@gmail.com',
            'customer_name' => 'Skyeface Digital Ltd',
            'payment_source' => 'Flutterwave',
            'paid_at' => '2026-01-24 23:46:11',
            'response_data' => json_encode([
                'id' => 9966043,
                'tx_ref' => 'REPAIR-23-1769294744',
                'amount' => 60000,
                'currency' => 'NGN',
                'status' => 'successful',
                'payment_type' => 'ussd',
            ]),
        ]);
        
        echo "✅ PAYMENT RECOVERED!\n\n";
        echo "Payment ID: " . $payment->id . "\n";
        echo "Amount: ₦" . $payment->amount . "\n";
        echo "Reference: " . $payment->reference . "\n";
        echo "Transaction ID: " . $payment->transaction_id . "\n";
        echo "Status: " . $payment->status . "\n";
        echo "Created: " . $payment->created_at . "\n";
        echo "Paid At: " . $payment->paid_at . "\n\n";
        
        // Verify repair
        $repair->update([
            'payment_status' => 'completed',
            'payment_verified_at' => now(),
            'payment_id' => $payment->id,
        ]);
        
        echo "✅ Repair #23 updated to 'completed' status\n";
        echo "\n💡 Payment is now visible on /admin/payments dashboard\n";
    }
} else {
    echo "❌ Repair #23 not found\n";
}

echo "\n";
