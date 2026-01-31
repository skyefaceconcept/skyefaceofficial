<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class TicketClosedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $ticket;

    /**
     * Create a new message instance.
     */
    public function __construct($ticket)
    {
        $this->ticket = $ticket;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->from(config('mail.from.address'), config('mail.from.name'))
                    ->subject('Your Support Ticket ' . ($this->ticket->ticket_number ?? '') . ' Has Been Closed')
                    ->view('emails.ticket_closed')
                    ->text('emails.ticket_closed_plain')
                    ->with([
                        'ticket' => $this->ticket,
                    ])
                    ->withSymfonyMessage(function ($message) {
                        $message->getHeaders()
                            ->addTextHeader('X-Priority', '3')
                            ->addTextHeader('X-Mailer', config('app.name'))
                            ->addTextHeader('List-Unsubscribe', '<' . url('/ticket/' . $this->ticket->ticket_number) . '>');
                    });
    }
}
