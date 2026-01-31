<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Quote extends Model
{
    use HasFactory;

    protected $fillable = [
        'package',
        'name',
        'email',
        'phone',
        'details',
        'status',
        'notified',
        'admin_notes',
        'quoted_price',
        'response',
        'ip_address',
        'responded_at',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'responded_at' => 'datetime',
    ];

    // Available statuses
    public const STATUS_NEW = 'new';
    public const STATUS_REVIEWED = 'reviewed';
    public const STATUS_QUOTED = 'quoted';
    public const STATUS_REJECTED = 'rejected';
    public const STATUS_ACCEPTED = 'accepted';

    public static function getStatuses()
    {
        return [
            self::STATUS_NEW => 'New',
            self::STATUS_REVIEWED => 'Reviewed',
            self::STATUS_QUOTED => 'Quoted',
            self::STATUS_REJECTED => 'Rejected',
            self::STATUS_ACCEPTED => 'Accepted',
        ];
    }

    public function getStatusBadgeClass()
    {
        return match($this->status) {
            self::STATUS_NEW => 'badge-warning',
            self::STATUS_REVIEWED => 'badge-info',
            self::STATUS_QUOTED => 'badge-success',
            self::STATUS_REJECTED => 'badge-danger',
            self::STATUS_ACCEPTED => 'badge-success',
            default => 'badge-secondary',
        };
    }

    /**
     * Get payments for this quote
     */
    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    /**
     * Check if quote has a completed payment
     */
    public function hasCompletedPayment()
    {
        return $this->payments()->where('status', 'completed')->exists();
    }
}
