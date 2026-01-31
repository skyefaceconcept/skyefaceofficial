<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailables\Headers;
use Illuminate\Queue\SerializesModels;
use App\Models\Repair;

class RepairBookingConfirmation extends Mailable
{
    use Queueable, SerializesModels;

    public Repair $repair;

    public function __construct(Repair $repair)
    {
        $this->repair = $repair;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            from: config('mail.from.address'),
            replyTo: [config('mail.from.address')],
            subject: 'Device Repair Booking Confirmed - ' . $this->repair->invoice_number,
        );
    }

    public function headers(): Headers
    {
        return new Headers(
            text: [
                'List-Unsubscribe' => '<' . route('home') . '>',
            ],
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.repairs.booking-confirmation',
            with: [
                'repair' => $this->repair,
            ],
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
