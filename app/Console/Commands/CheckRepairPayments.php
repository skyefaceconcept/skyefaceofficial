<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Payment;
use App\Models\Repair;

class CheckRepairPayments extends Command
{
    protected $signature = 'check:repair-payments';
    protected $description = 'Check repair payment records';

    public function handle()
    {
        $this->line('=== Repairs with payment_reference ===');
        $repairs = Repair::whereNotNull('payment_reference')->get(['id', 'invoice_number', 'payment_reference', 'payment_status']);
        foreach ($repairs as $r) {
            $this->line("Repair #{$r->id}: {$r->invoice_number}, Ref: {$r->payment_reference}, Status: {$r->payment_status}");
        }

        $this->line("\n=== All Payments in DB ===");
        $payments = Payment::all(['id', 'quote_id', 'repair_id', 'reference', 'status', 'payment_source']);
        $this->line("Total: " . count($payments));
        foreach ($payments as $p) {
            $type = $p->quote_id ? "QUOTE" : ($p->repair_id ? "REPAIR" : "UNKNOWN");
            $this->line("Payment #{$p->id}: {$type}, Ref: {$p->reference}, Status: {$p->status}");
        }

        $this->line("\n=== Repair Payments Only ===");
        $repairPayments = Payment::whereNotNull('repair_id')->get(['id', 'repair_id', 'reference', 'status']);
        $this->line("Count: " . count($repairPayments));
        foreach ($repairPayments as $p) {
            $this->line("Payment #{$p->id}: Repair #{$p->repair_id}");
        }
    }
}
