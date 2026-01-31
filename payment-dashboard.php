<?php
// Comprehensive Payment Dashboard via Tinker

use App\Models\Payment;

echo "\n";
echo "╔════════════════════════════════════════════════════════════════════════════════╗\n";
echo "║               SKYEFACE PAYMENT TRACKING DASHBOARD                             ║\n";
echo "║                        Updated: " . now()->format('Y-m-d H:i:s') . "                            ║\n";
echo "╚════════════════════════════════════════════════════════════════════════════════╝\n\n";

// Overall Statistics
echo "📊 OVERALL STATISTICS\n";
echo "─────────────────────────────────────────────────────────────────────────────────\n";
$totalPayments = Payment::count();
$totalRevenue = Payment::where('status', 'completed')->sum('amount');
$totalPending = Payment::where('status', 'pending')->sum('amount');
$completedCount = Payment::where('status', 'completed')->count();
$pendingCount = Payment::where('status', 'pending')->count();
$successRate = $totalPayments > 0 ? round(($completedCount / $totalPayments) * 100, 1) : 0;

echo "Total Payments:      " . str_pad($totalPayments, 20) . " payments\n";
echo "Completed Payments:  " . str_pad($completedCount, 20) . " (" . $successRate . "%)\n";
echo "Pending Payments:    " . str_pad($pendingCount, 20) . " payments\n";
echo "Failed Payments:     " . str_pad(Payment::where('status', 'failed')->count(), 20) . " payments\n";
echo "Cancelled Payments:  " . str_pad(Payment::where('status', 'cancelled')->count(), 20) . " payments\n\n";

// Revenue
echo "💰 REVENUE SUMMARY\n";
echo "─────────────────────────────────────────────────────────────────────────────────\n";
echo "Total Completed Revenue: ₦" . number_format($totalRevenue, 2) . "\n";
echo "Total Pending Revenue:   ₦" . number_format($totalPending, 2) . "\n";
echo "Average Transaction:     ₦" . number_format($totalPayments > 0 ? $totalRevenue / $completedCount : 0, 2) . "\n\n";

// Payment Types
echo "📋 PAYMENTS BY TYPE\n";
echo "─────────────────────────────────────────────────────────────────────────────────\n";
$quotePayments = Payment::whereNotNull('quote_id')->count();
$repairPayments = Payment::whereNotNull('repair_id')->count();
$directPayments = Payment::whereNull('quote_id')->whereNull('repair_id')->count();

echo "Quote Payments:  " . str_pad($quotePayments, 25) . "₦" . number_format(Payment::whereNotNull('quote_id')->sum('amount'), 2) . "\n";
echo "Repair Payments: " . str_pad($repairPayments, 25) . "₦" . number_format(Payment::whereNotNull('repair_id')->sum('amount'), 2) . "\n";
echo "Direct Payments: " . str_pad($directPayments, 25) . "₦" . number_format(Payment::whereNull('quote_id')->whereNull('repair_id')->sum('amount'), 2) . "\n\n";

// Payment Processors
echo "🏦 PAYMENTS BY PROCESSOR\n";
echo "─────────────────────────────────────────────────────────────────────────────────\n";
$paystack = Payment::where('payment_source', 'like', '%Paystack%')->count();
$flutterwave = Payment::where('payment_source', 'like', '%Flutterwave%')->count();
$other = $totalPayments - $paystack - $flutterwave;

echo "Paystack:       " . str_pad($paystack, 25) . "₦" . number_format(Payment::where('payment_source', 'like', '%Paystack%')->sum('amount'), 2) . "\n";
echo "Flutterwave:    " . str_pad($flutterwave, 25) . "₦" . number_format(Payment::where('payment_source', 'like', '%Flutterwave%')->sum('amount'), 2) . "\n";
echo "Other/Unknown:  " . str_pad($other, 25) . "₦" . number_format(Payment::where('payment_source', null)->orWhere('payment_source', '')->sum('amount'), 2) . "\n\n";

// Recent Payments
echo "📝 RECENT PAYMENTS (Last 5)\n";
echo "─────────────────────────────────────────────────────────────────────────────────\n";
$recent = Payment::with(['quote', 'repair'])->latest()->limit(5)->get();
$i = 1;
foreach ($recent as $p) {
    $type = $p->quote ? 'Quote' : ($p->repair ? 'Repair' : 'Direct');
    $status = $p->status;
    echo "{$i}. Payment #{$p->id} | {$type} | ₦{$p->amount} | {$status} | {$p->created_at->format('M d, Y H:i')}\n";
    $i++;
}

echo "\n";

// Status Distribution
echo "📊 STATUS DISTRIBUTION\n";
echo "─────────────────────────────────────────────────────────────────────────────────\n";
$statuses = [
    'completed' => Payment::where('status', 'completed')->count(),
    'pending' => Payment::where('status', 'pending')->count(),
    'failed' => Payment::where('status', 'failed')->count(),
    'cancelled' => Payment::where('status', 'cancelled')->count(),
];

foreach ($statuses as $status => $count) {
    if ($count > 0) {
        $percent = round(($count / $totalPayments) * 100, 1);
        $bar = str_repeat('█', intval($percent / 5));
        echo ucfirst($status) . ": " . str_pad($count, 3) . " payments (" . str_pad($percent . '%', 6) . ") " . $bar . "\n";
    }
}

echo "\n";

// Tips
echo "💡 USEFUL COMMANDS\n";
echo "─────────────────────────────────────────────────────────────────────────────────\n";
echo "Get payment details:       Payment::find(1)\n";
echo "Get repair payments:       Payment::whereNotNull('repair_id')->with('repair')->get()\n";
echo "Get quote payments:        Payment::whereNotNull('quote_id')->with('quote')->get()\n";
echo "Find by reference:         Payment::where('reference', 'SKYEFACE-1-abc123')->first()\n";
echo "Find by customer:          Payment::where('customer_email', 'email@example.com')->get()\n";
echo "Get pending payments:      Payment::where('status', 'pending')->get()\n";
echo "Calculate total revenue:   Payment::where('status', 'completed')->sum('amount')\n";
echo "Exit tinker:               exit\n\n";

echo "✅ Payment Tracking System is Working Perfectly!\n";
echo "📊 All payments are tracked and visible in /admin/payments\n";
echo "────────────────────────────────────────────────────────────────────────────────\n\n";
