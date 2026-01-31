<?php

namespace App\Events;

use App\Models\ContactMessage;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;

class ContactMessageCreated implements ShouldBroadcastNow
{
    use InteractsWithSockets, SerializesModels;

    public $message;
    public $ticketId;

    /**
     * Create a new event instance.
     */
    public function __construct(ContactMessage $message)
    {
        $this->message = $message;
        $this->ticketId = $message->ticket_id;
    }

    /**
     * Get the channels the event should broadcast on.
     */
    public function broadcastOn()
    {
        return new PrivateChannel('contact-ticket.' . $this->ticketId);
    }

    /**
     * The event's broadcast name.
     */
    public function broadcastAs()
    {
        return 'ContactMessageCreated';
    }

    /**
     * Data to broadcast with the event.
     */
    public function broadcastWith()
    {
        return [
            'id' => $this->message->id,
            'ticket_id' => $this->ticketId,
            'sender_type' => $this->message->sender_type,
            'message' => $this->message->message,
            'created_at' => $this->message->created_at->format('M d, Y H:i'),
        ];
    }
}
