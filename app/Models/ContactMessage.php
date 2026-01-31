<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContactMessage extends Model
{
    use HasFactory;

    protected $fillable = [
        'ticket_id',
        'sender_type',
        'sender_id',
        'message',
        'attachments',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'attachments' => 'json',
    ];

    /**
     * Get the ticket this message belongs to
     */
    public function ticket()
    {
        return $this->belongsTo(ContactTicket::class, 'ticket_id');
    }

    /**
     * Get the sender (if it's an admin user)
     */
    public function sender()
    {
        if ($this->sender_type === 'admin') {
            return $this->belongsTo(User::class, 'sender_id');
        }
        return null;
    }

    /**
     * Scope to get messages from clients
     */
    public function scopeFromClient($query)
    {
        return $query->where('sender_type', 'client');
    }

    /**
     * Scope to get messages from admin
     */
    public function scopeFromAdmin($query)
    {
        return $query->where('sender_type', 'admin');
    }
}
