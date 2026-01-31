<?php

namespace App\Mail;

use App\Models\ContactTicket;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ContactFormNotifyAdmin extends Mailable
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
                    ->subject('New Contact Form Submission - Ticket #' . $this->ticket->ticket_number)
                    ->view('emails.contact_form_admin_notification')
                    ->with([
                        'ticket' => $this->ticket,
                    ]);
    }
}
