<?php

namespace App\Mail;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class OrderConfirmationMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $order;
    public $license;

    /**
     * Create a new message instance.
     */
    public function __construct(Order $order)
    {
        $this->order = $order;
        // Load license if it already exists (in case order was completed before email)
        $this->license = $order->license;
    }

    /**
     * Get the message envelope.
     */
    public function envelope()
    {
        return new \Illuminate\Mail\Mailables\Envelope(
            subject: 'Order Confirmation - ' . $this->order->portfolio->title,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content()
    {
        return new \Illuminate\Mail\Mailables\Content(
            view: 'emails.order-confirmation',
            with: [
                'order' => $this->order,
                'portfolio' => $this->order->portfolio,
                'license' => $this->license,
                'paymentLink' => $this->getPaymentLink(),
            ],
        );
    }

    /**
     * Get the attachments for the message.
     */
    public function attachments()
    {
        return [];
    }

    /**
     * Get payment link based on processor
     */
    private function getPaymentLink()
    {
        return route('payment.show', ['order' => $this->order->id]);
    }
}
