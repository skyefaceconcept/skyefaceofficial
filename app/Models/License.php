<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class License extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'license_code',
        'application_name',
        'expiry_date',
        'status',
        'activation_count',
        'last_activated_ip',
        'last_activated_at',
        'metadata',
    ];

    protected $casts = [
        'metadata' => 'json',
        'expiry_date' => 'date',
        'last_activated_at' => 'datetime',
    ];

    // Status constants
    public const STATUS_ACTIVE = 'active';
    public const STATUS_INACTIVE = 'inactive';
    public const STATUS_EXPIRED = 'expired';
    public const STATUS_REVOKED = 'revoked';

    /**
     * Get the order this license belongs to
     */
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * Check if license is active and valid
     */
    public function isValid()
    {
        if ($this->status !== self::STATUS_ACTIVE) {
            return false;
        }

        if ($this->expiry_date && $this->expiry_date->isPast()) {
            $this->update(['status' => self::STATUS_EXPIRED]);
            return false;
        }

        return true;
    }

    /**
     * Record license activation
     */
    public function recordActivation($ip = null)
    {
        if (!$this->isValid()) {
            return false;
        }

        $this->update([
            'activation_count' => $this->activation_count + 1,
            'last_activated_ip' => $ip ?? request()->ip(),
            'last_activated_at' => now(),
            'metadata' => array_merge($this->metadata ?? [], [
                'activations' => [
                    'ip' => $ip ?? request()->ip(),
                    'user_agent' => request()->userAgent(),
                    'timestamp' => now()->toIso8601String(),
                ]
            ]),
        ]);

        return true;
    }

    /**
     * Check if license is expired
     */
    public function isExpired()
    {
        return $this->status === self::STATUS_EXPIRED ||
               ($this->expiry_date && $this->expiry_date->isPast());
    }

    /**
     * Revoke the license
     */
    public function revoke()
    {
        return $this->update(['status' => self::STATUS_REVOKED]);
    }
}
