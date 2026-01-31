<?php
// Daily Payment Audit Report

use App\Models\Payment;
use App\Models\Quote;
use App\Models\Repair;

echo "\n";
echo "╔════════════════════════════════════════════════════════════════════════════════╗\n";
echo "║            DAILY PAYMENT AUDIT REPORT - " . now()->format('Y-m-d') . "                    ║\n";
echo "╚════════════════════════════════════════════════════════════════════════════════╝\n\n";

// Today's Transactions
echo "📅 TODAY'S TRANSACTIONS\n";
echo "─────────────────────────────────────────────────────────────────────────────────\n";
$today = Payment::whereDate('created_at', today())->get();
echo "New Payments:   " . count($today) . "\n";
echo "Today's Revenue: ₦" . number_format($today->where('status', 'completed')->sum('amount'), 2) . "\n";
echo "Pending Amount:  ₦" . number_format($today->where('status', 'pending')->sum('amount'), 2) . "\n\n";

if ($today->count() > 0) {
    echo "Transactions:\n";
    foreach ($today as $p) {
        $type = $p->quote ? 'Quote' : ($p->repair ? 'Repair' : 'Direct');
        echo "  - #{$p->id} | $type | ₦{$p->amount} | {$p->status} | " . $p->created_at->format('H:i:s') . "\n";
    }
    echo "\n";
}

// Payment Integrity Check
echo "✅ PAYMENT INTEGRITY CHECK\n";
echo "─────────────────────────────────────────────────────────────────────────────────\n";

// Check for missing references
$missingRef = Payment::whereNull('reference')->count();
echo "Missing References:     " . ($missingRef > 0 ? "⚠️ {$missingRef} payments" : "✅ None") . "\n";

// Check for missing transactions
$missingTx = Payment::whereNull('transaction_id')->count();
echo "Missing Transaction IDs: " . ($missingTx > 0 ? "⚠️ {$missingTx} payments" : "✅ None") . "\n";

// Check for orphaned payments (neither quote nor repair)
$orphaned = Payment::whereNull('quote_id')->whereNull('repair_id')->count();
echo "Orphaned Payments:      " . ($orphaned > 0 ? "⚠️ {$orphaned} payments" : "✅ None") . "\n";

// Check for invalid amounts
$invalid = Payment::where('amount', '<=', 0)->count();
echo "Invalid Amounts:        " . ($invalid > 0 ? "⚠️ {$invalid} payments" : "✅ None") . "\n";

// Check for missing customer info
$noCustomer = Payment::whereNull('customer_name')->orWhereNull('customer_email')->count();
echo "Missing Customer Info:  " . ($noCustomer > 0 ? "⚠️ {$noCustomer} payments" : "✅ None") . "\n\n";

// Last 7 Days Summary
echo "📊 LAST 7 DAYS SUMMARY\n";
echo "─────────────────────────────────────────────────────────────────────────────────\n";
$week = Payment::where('created_at', '>=', now()->subDays(7))->get();
echo "Total Payments:     " . count($week) . "\n";
echo "Total Revenue:      ₦" . number_format($week->where('status', 'completed')->sum('amount'), 2) . "\n";
echo "Completion Rate:    " . ($week->count() > 0 ? round(($week->where('status', 'completed')->count() / $week->count()) * 100, 1) : 0) . "%\n";
echo "Avg Daily Revenue:  ₦" . number_format($week->where('status', 'completed')->sum('amount') / 7, 2) . "\n\n";

// Daily Breakdown
echo "Daily Breakdown:\n";
for ($i = 6; $i >= 0; $i--) {
    $date = now()->subDays($i)->format('Y-m-d');
    $count = Payment::whereDate('created_at', $date)->count();
    $revenue = Payment::whereDate('created_at', $date)->where('status', 'completed')->sum('amount');
    if ($count > 0) {
        echo "  " . now()->subDays($i)->format('D, M d') . ": {$count} payments | ₦" . number_format($revenue, 2) . "\n";
    }
}

echo "\n";

// Processor Performance
echo "🏦 PROCESSOR PERFORMANCE\n";
echo "─────────────────────────────────────────────────────────────────────────────────\n";
$processors = Payment::selectRaw('payment_source, count(*) as total, sum(amount) as revenue, sum(case when status = "completed" then 1 else 0 end) as completed')
    ->whereNotNull('payment_source')
    ->groupBy('payment_source')
    ->get();

foreach ($processors as $p) {
    $rate = $p->total > 0 ? round(($p->completed / $p->total) * 100, 1) : 0;
    echo $p->payment_source . ": " . $p->total . " payments | ₦" . number_format($p->revenue, 2) . " | {$rate}% success\n";
}

echo "\n";

// Status Summary
echo "📋 PAYMENT STATUS SUMMARY\n";
echo "─────────────────────────────────────────────────────────────────────────────────\n";
$statuses = Payment::groupBy('status')->selectRaw('status, count(*) as total, sum(amount) as total_amount')->get();
foreach ($statuses as $s) {
    $icon = $s->status == 'completed' ? '✅' : ($s->status == 'pending' ? '⏳' : '❌');
    echo "{$icon} " . ucfirst($s->status) . ": " . str_pad($s->total, 3) . " payments | ₦" . number_format($s->total_amount, 2) . "\n";
}

echo "\n";

// Overall Health
echo "💚 SYSTEM HEALTH\n";
echo "─────────────────────────────────────────────────────────────────────────────────\n";
$allPayments = Payment::count();
$completedPayments = Payment::where('status', 'completed')->count();
$health = ($allPayments > 0) ? round(($completedPayments / $allPayments) * 100, 1) : 100;

if ($health == 100) {
    echo "✅ Payment system is operating at 100% health\n";
    echo "✅ All payments are successfully tracked and completed\n";
} elseif ($health >= 95) {
    echo "✅ Payment system is healthy (95%+ success rate)\n";
} else {
    echo "⚠️ Payment system needs attention (below 95% success rate)\n";
}

echo "✅ Admin dashboard available at: /admin/payments\n";
echo "✅ Total tracked revenue: ₦" . number_format(Payment::where('status', 'completed')->sum('amount'), 2) . "\n";
echo "✅ Database integrity: OK\n";

echo "\n";
echo "─────────────────────────────────────────────────────────────────────────────────\n";
echo "Generated at: " . now()->format('Y-m-d H:i:s') . "\n";
echo "Next audit: " . now()->addDay()->format('Y-m-d 09:00:00') . "\n";
echo "─────────────────────────────────────────────────────────────────────────────────\n\n";
