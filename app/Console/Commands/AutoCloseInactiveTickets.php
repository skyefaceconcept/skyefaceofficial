<?php

namespace App\Console\Commands;

use App\Models\ContactTicket;
use Illuminate\Console\Command;

class AutoCloseInactiveTickets extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tickets:auto-close';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Automatically close contact tickets that have not received a reply for 2 days';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $inactiveTickets = ContactTicket::inactiveForAutoClose()->get();

        $count = 0;
        foreach ($inactiveTickets as $ticket) {
            if ($ticket->shouldAutoClose()) {
                $ticket->autoClose();
                $count++;
                $this->line("Closed ticket: {$ticket->ticket_number}");
            }
        }

        if ($count > 0) {
            $this->info("Successfully closed {$count} inactive ticket(s).");
        } else {
            $this->info('No inactive tickets found to close.');
        }

        return 0;
    }
}
