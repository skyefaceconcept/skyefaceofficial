<?php

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Repair;
use App\Mail\RepairStatusUpdate;
use Illuminate\Support\Facades\Mail;

echo "\n";
echo "╔════════════════════════════════════════════════════════╗\n";
echo "║  REPAIR COMPLETED EMAIL - SPAM FIX TEST                ║\n";
echo "║  Testing with optimized version                        ║\n";
echo "╚════════════════════════════════════════════════════════╝\n\n";

try {
    $repair = Repair::first();
    if (!$repair) {
        echo "ERROR: No repair record found\n";
        exit(1);
    }
    
    echo "Sending optimized Repair Completed email...\n\n";
    
    // Send the Completed status email
    Mail::send(new RepairStatusUpdate($repair, 'Completed', 'Re-test with optimized content - ' . date('Y-m-d H:i:s')));
    
    echo "✓ Email sent successfully!\n\n";
    
    // Get latest email from database
    $latest = \DB::table('mail_logs')
        ->where('to', $repair->customer_email)
        ->orderBy('created_at', 'desc')
        ->first();
    
    if ($latest) {
        echo "📧 Email Details:\n";
        echo "  Database ID: " . $latest->id . "\n";
        echo "  To: " . $latest->to . "\n";
        echo "  Subject: " . $latest->subject . "\n";
        echo "  Sent At: " . $latest->created_at . "\n\n";
    }
    
    echo "════════════════════════════════════════════════════════\n\n";
    
    echo "🔧 CHANGES MADE TO FIX SPAM FILTERING:\n\n";
    
    echo "Removed spam trigger words:\n";
    echo "  ❌ 'Congratulations' → simplified greeting\n";
    echo "  ❌ 'Claim' → removed from warranty section\n";
    echo "  ❌ 'File a Claim' → changed to 'Contact us'\n";
    echo "  ❌ 'for free' → removed (spam trigger)\n";
    echo "  ❌ 'Hours:' → changed to date-based format\n";
    echo "  ❌ Multiple links → removed (Review button)\n";
    echo "  ❌ Emotional language → made more neutral\n\n";
    
    echo "Improved formatting:\n";
    echo "  ✓ Simpler, more direct language\n";
    echo "  ✓ Removed unnecessary exclamation marks\n";
    echo "  ✓ Removed 'immediately with photos' (urgency trigger)\n";
    echo "  ✓ Streamlined warranty section\n";
    echo "  ✓ Removed emoji and special formatting\n\n";
    
    echo "════════════════════════════════════════════════════════\n\n";
    
    echo "📊 NEXT STEPS:\n\n";
    echo "1. Check your email (skyefacecon@gmail.com)\n";
    echo "2. Look for: 'Update: Repair Completed - REP-OA0-20260122-3790'\n";
    echo "3. Check if it's in INBOX or SPAM folder\n";
    echo "4. Report: ✓ INBOX or 🔴 SPAM\n\n";
    
    echo "If it's now in INBOX:\n";
    echo "  ✅ Problem solved! The issue was spam trigger words.\n\n";
    
    echo "If still in SPAM:\n";
    echo "  ⚠️ May need DNS records (SPF/DKIM/DMARC) as root cause\n";
    echo "  ⚠️ OR email provider reputation/blacklist issue\n\n";
    
    echo "════════════════════════════════════════════════════════\n\n";
    
    $total = \DB::table('mail_logs')->where('to', $repair->customer_email)->count();
    echo "Total test emails now in system: $total\n";
    echo "\n✓ Test email sent and logged!\n\n";
    
} catch (\Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
    exit(1);
}
