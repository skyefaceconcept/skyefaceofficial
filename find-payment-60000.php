<?php
use App\Models\Payment;

echo "\n=== SEARCHING FOR 60000 PAYMENT ===\n\n";

$payment = Payment::where('amount', 60000)->first();

if ($payment) {
    echo "✅ FOUND PAYMENT!\n\n";
    echo "Payment ID: " . $payment->id . "\n";
    echo "Amount: ₦" . $payment->amount . "\n";
    echo "Status: " . $payment->status . "\n";
    echo "Reference: " . ($payment->reference ?? 'N/A') . "\n";
    echo "Transaction ID: " . ($payment->transaction_id ?? 'N/A') . "\n";
    echo "Payment Source: " . ($payment->payment_source ?? 'N/A') . "\n";
    echo "Customer Name: " . ($payment->customer_name ?? 'N/A') . "\n";
    echo "Customer Email: " . ($payment->customer_email ?? 'N/A') . "\n";
    echo "Quote ID: " . ($payment->quote_id ?? 'None') . "\n";
    echo "Repair ID: " . ($payment->repair_id ?? 'None') . "\n";
    echo "Created: " . $payment->created_at . "\n";
    echo "Paid At: " . ($payment->paid_at ?? 'Not recorded') . "\n";
} else {
    echo "❌ Payment of 60000 NOT FOUND in database\n\n";
    echo "Searching for similar amounts...\n";
    $similar = Payment::whereBetween('amount', [59000, 61000])->get();
    if ($similar->count() > 0) {
        echo "\nFound " . $similar->count() . " similar payment(s):\n";
        foreach ($similar as $p) {
            echo "  - Payment #{$p->id}: ₦{$p->amount} | Status: {$p->status} | Reference: {$p->reference}\n";
        }
    } else {
        echo "\n❌ No similar payments found either\n";
        
        echo "\nLatest 5 payments in system:\n";
        $latest = Payment::latest()->limit(5)->get();
        foreach ($latest as $p) {
            echo "  - Payment #{$p->id}: ₦{$p->amount} | Status: {$p->status} | " . $p->created_at->diffForHumans() . "\n";
        }
    }
}

echo "\n";
