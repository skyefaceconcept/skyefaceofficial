<?php

namespace App\Mail;

use App\Models\Quote;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class QuoteResponseEmail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public function __construct(public Quote $quote)
    {
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        $statusText = $this->quote->status === 'quoted' ? 'Quote Provided' : 'Update';
        return new Envelope(
            subject: "Re: Your Quote Request - #{$this->quote->id} - {$statusText}",
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.quotes.admin-response',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
