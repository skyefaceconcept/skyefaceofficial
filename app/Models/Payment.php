<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'quote_id',
        'repair_id',
        'order_id',
        'amount',
        'currency',
        'status',
        'transaction_id',
        'transaction_reference',
        'reference',
        'processor',
        'customer_email',
        'customer_name',
        'payment_method',
        'payment_source',
        'response_data',
        'paid_at',
    ];

    protected $casts = [
        'response_data' => 'json',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'paid_at' => 'datetime',
    ];

    // Payment statuses
    public const STATUS_PENDING = 'pending';
    public const STATUS_COMPLETED = 'completed';
    public const STATUS_FAILED = 'failed';
    public const STATUS_CANCELLED = 'cancelled';

    /**
     * Boot method to ensure transaction_id is never empty
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->transaction_id)) {
                $model->transaction_id = (string) Str::orderedUuid();
            }
        });

        static::updating(function ($model) {
            if (empty($model->transaction_id)) {
                $model->transaction_id = (string) Str::orderedUuid();
            }
        });
    }

    public static function getStatuses()
    {
        return [
            self::STATUS_PENDING => 'Pending',
            self::STATUS_COMPLETED => 'Completed',
            self::STATUS_FAILED => 'Failed',
            self::STATUS_CANCELLED => 'Cancelled',
        ];
    }

    /**
     * Get the quote associated with this payment
     */
    public function quote()
    {
        return $this->belongsTo(Quote::class);
    }

    /**
     * Get the repair associated with this payment
     */
    public function repair()
    {
        return $this->belongsTo(Repair::class);
    }

    /**
     * Get the order associated with this payment
     */
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * Check if payment is completed
     */
    public function isCompleted()
    {
        return $this->status === self::STATUS_COMPLETED;
    }

    /**
     * Get payment status badge
     */
    public function getStatusBadgeClass()
    {
        return match($this->status) {
            self::STATUS_COMPLETED => 'badge-success',
            self::STATUS_PENDING => 'badge-warning',
            self::STATUS_FAILED => 'badge-danger',
            self::STATUS_CANCELLED => 'badge-secondary',
            default => 'badge-secondary',
        };
    }
}
