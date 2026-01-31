<?php
use App\Models\Payment;
use App\Models\Repair;

echo "\n╔════════════════════════════════════════════════════════════════════════════════╗\n";
echo "║                  FINDING & RECOVERING ALL MISSING PAYMENTS                    ║\n";
echo "╚════════════════════════════════════════════════════════════════════════════════╝\n\n";

$missingPayments = array(
    array(
        'name' => 'Paystack 200000',
        'transaction_id' => '5769643907',
        'reference' => 'REPAIR-23-1769293435',
        'amount' => 200000,
        'processor' => 'Paystack',
        'repair_id' => 23,
        'email' => 'skyefacecon@gmail.com',
        'customer_name' => 'John Doe',
        'paid_at' => '2026-01-24 22:24:07',
    ),
    array(
        'name' => 'Flutterwave 2000',
        'transaction_id' => '9966038',
        'reference' => 'REPAIR-23-1769294192',
        'amount' => 2000,
        'processor' => 'Flutterwave',
        'repair_id' => 23,
        'email' => 'ravesb_64b3ab6ca8f152fbe22b_skyefaceconcept@gmail.com',
        'customer_name' => 'Skyeface Digital Ltd',
        'paid_at' => '2026-01-24 22:39:37',
    ),
);

$recovered = 0;
$skipped = 0;

foreach ($missingPayments as $p) {
    echo "Processing: " . $p['name'] . "\n";
    echo "─────────────────────────────────────────────────────────────────────────────────\n";
    
    // Check if payment already exists
    $existing = Payment::where('transaction_id', $p['transaction_id'])->first();
    
    if ($existing) {
        echo "⏭️  Payment already exists (ID: " . $existing->id . ")\n\n";
        $skipped++;
    } else {
        // Create the payment
        $payment = Payment::create([
            'repair_id' => $p['repair_id'],
            'transaction_id' => $p['transaction_id'],
            'amount' => $p['amount'],
            'currency' => 'NGN',
            'status' => 'completed',
            'reference' => $p['reference'],
            'customer_email' => $p['email'],
            'customer_name' => $p['customer_name'],
            'payment_source' => $p['processor'],
            'paid_at' => $p['paid_at'],
            'response_data' => json_encode([
                'amount' => $p['amount'],
                'status' => 'successful',
                'processor' => $p['processor'],
            ]),
        ]);
        
        // Update repair
        $repair = Repair::find($p['repair_id']);
        if ($repair) {
            $repair->update([
                'payment_status' => 'completed',
                'payment_verified_at' => now(),
                'payment_id' => $payment->id,
            ]);
        }
        
        echo "✅ RECOVERED! Payment ID: " . $payment->id . "\n";
        echo "   Amount: ₦" . $p['amount'] . "\n";
        echo "   Processor: " . $p['processor'] . "\n";
        echo "   Paid: " . $p['paid_at'] . "\n\n";
        $recovered++;
    }
}

echo "╔════════════════════════════════════════════════════════════════════════════════╗\n";
echo "║                              RECOVERY SUMMARY                                 ║\n";
echo "╠════════════════════════════════════════════════════════════════════════════════╣\n";
echo "║ Recovered: " . str_pad($recovered, 71) . "║\n";
echo "║ Already Existed: " . str_pad($skipped, 66) . "║\n";
echo "║ Total Processed: " . str_pad($recovered + $skipped, 65) . "║\n";
echo "╚════════════════════════════════════════════════════════════════════════════════╝\n";

// Show updated stats
echo "\n📊 UPDATED PAYMENT STATISTICS\n";
echo "─────────────────────────────────────────────────────────────────────────────────\n";
$total = Payment::count();
$revenue = Payment::where('status', 'completed')->sum('amount');
$repairs = Payment::whereNotNull('repair_id')->count();
$quotes = Payment::whereNotNull('quote_id')->count();

echo "Total Payments: " . str_pad($total, 61) . "\n";
echo "Total Revenue: ₦" . str_pad(number_format($revenue, 2), 66) . "\n";
echo "Repair Payments: " . str_pad($repairs, 65) . "\n";
echo "Quote Payments: " . str_pad($quotes, 66) . "\n";
echo "─────────────────────────────────────────────────────────────────────────────────\n\n";

echo "✅ All missing payments have been processed!\n";
echo "💡 Visit /admin/payments to see all payments\n\n";
