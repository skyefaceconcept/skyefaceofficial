<?php

namespace App\Mail;

use App\Models\ContactTicket;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ContactFormSubmitted extends Mailable
{
    use Queueable, SerializesModels;

    public $ticket;

    /**
     * Create a new message instance.
     */
    public function __construct(ContactTicket $ticket)
    {
        $this->ticket = $ticket;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->from(config('mail.from.address'), config('mail.from.name'))
                    ->subject('We Received Your Message - Ticket #' . $this->ticket->ticket_number)
                    ->view('emails.contact_form_submitted')
                    ->with([
                        'ticket' => $this->ticket,
                    ]);
    }
}
