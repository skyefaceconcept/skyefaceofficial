<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContactTicket extends Model
{
    use HasFactory;

    protected $fillable = [
        'ticket_number',
        'user_email',
        'user_name',
        'phone',
        'subject',
        'status',
        'assigned_to',
        'last_reply_date',
        'auto_closed_at',
    ];

    protected $casts = [
        'last_reply_date' => 'datetime',
        'auto_closed_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the messages for this ticket
     */
    public function messages()
    {
        return $this->hasMany(ContactMessage::class, 'ticket_id');
    }

    /**
     * Get the admin user assigned to this ticket
     */
    public function assignedAdmin()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    /**
     * Scope to get open tickets
     */
    public function scopeOpen($query)
    {
        return $query->whereIn('status', ['open', 'pending_reply']);
    }

    /**
     * Scope to get tickets that should be auto-closed (no reply for 2 days)
     */
    public function scopeInactiveForAutoClose($query)
    {
        return $query->where('status', 'open')
                    ->where('last_reply_date', '<=', now()->subDays(2));
    }

    /**
     * Check if ticket should be auto-closed
     */
    public function shouldAutoClose()
    {
        return $this->status === 'open' &&
               $this->last_reply_date &&
               $this->last_reply_date->diffInDays(now()) >= 2;
    }

    /**
     * Auto-close the ticket
     */
    public function autoClose()
    {
        $this->update([
            'status' => 'closed',
            'auto_closed_at' => now(),
        ]);
    }
}
