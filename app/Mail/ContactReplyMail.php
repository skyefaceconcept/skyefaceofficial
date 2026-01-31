<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ContactReplyMail extends Mailable
{
    use Queueable, SerializesModels;

    public $ticket;
    public $messageModel;

    /**
     * Create a new message instance.
     */
    public function __construct($ticket, $messageModel)
    {
        $this->ticket = $ticket;
        $this->messageModel = $messageModel;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->from(config('mail.from.address'), config('mail.from.name'))
                    ->subject('New Reply to Your Support Ticket - ' . ($this->ticket->ticket_number ?? ''))
                    ->view('emails.contact_reply')
                    ->text('emails.contact_reply_plain')
                    ->with([
                        'ticket' => $this->ticket,
                        'messageModel' => $this->messageModel,
                    ])
                    ->withSymfonyMessage(function ($message) {
                        $message->getHeaders()
                            ->addTextHeader('X-Priority', '3')
                            ->addTextHeader('X-Mailer', config('app.name'))
                            ->addTextHeader('List-Unsubscribe', '<' . url('/ticket/' . $this->ticket->ticket_number) . '>');
                    });
    }
}
