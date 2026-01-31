<?php

namespace App\Mail;

use App\Models\Payment;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PaymentCompletedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $payment;
    public $quote;

    /**
     * Create a new message instance.
     */
    public function __construct(Payment $payment)
    {
        $this->payment = $payment;
        $this->quote = $payment->quote;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        $subject = 'Payment Confirmation - ';
        if ($this->quote) {
            $subject .= 'Invoice #' . $this->quote->id;
        } else {
            $subject .= 'Payment #' . $this->payment->id;
        }

        return new Envelope(
            subject: $subject,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        if ($this->quote) {
            return new Content(
                view: 'emails.payments.completed',
                with: [
                    'payment' => $this->payment,
                    'quote' => $this->quote,
                    'customerName' => $this->quote->name,
                    'customerEmail' => $this->quote->email,
                    'amount' => $this->payment->amount,
                    'currency' => $this->payment->currency,
                    'reference' => $this->payment->reference,
                    'transactionId' => $this->payment->transaction_id,
                ],
            );
        }

        // Fallback for non-quote payments (orders, repairs etc.)
        return new Content(
            view: 'emails.payments.completed_generic',
            with: [
                'payment' => $this->payment,
                'customerName' => $this->payment->customer_name ?? $this->payment->customer_name,
                'customerEmail' => $this->payment->customer_email ?? $this->payment->customer_email,
                'amount' => $this->payment->amount,
                'currency' => $this->payment->currency,
                'reference' => $this->payment->reference,
                'transactionId' => $this->payment->transaction_id,
            ],
        );
    }

    /**
     * Get the attachments for the message.
     */
    public function attachments(): array
    {
        return [];
    }
}
