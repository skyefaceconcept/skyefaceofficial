<?php

namespace App\Mail;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class OrderCompletedMail extends Mailable implements ShouldQueue
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
        $this->license = $order->license;
    }

    /**
     * Get the message envelope.
     */
    public function envelope()
    {
        $portfolioTitle = $this->order->portfolio?->title ?? 'Order';
        return new \Illuminate\Mail\Mailables\Envelope(
            to: $this->order->customer_email,
            subject: 'Your Order is Complete - ' . $portfolioTitle,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content()
    {
        return new \Illuminate\Mail\Mailables\Content(
            view: 'emails.order-completed-simple',
            with: [
                'order' => $this->order,
                'portfolio' => $this->order->portfolio ?? null,
                'license' => $this->license ?? null,
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
}
