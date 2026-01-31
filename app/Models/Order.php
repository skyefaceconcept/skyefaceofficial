<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Mail;
use App\Mail\OrderConfirmationMail;
use App\Mail\OrderCompletedMail;
use App\Mail\LicenseActivationMail;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'invoice_number',
        'portfolio_id',
        'user_id',
        'billing_address_id',
        'customer_name',
        'customer_email',
        'customer_phone',
        'address',
        'city',
        'state',
        'zip',
        'country',
        'amount',
        'currency',
        'status',
        'license_duration',
        'transaction_reference',
        'payment_method',
        'payment_processor',
        'cart_items',
        'notes',
        'completed_at',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'completed_at' => 'datetime',
    ];

    // Status constants
    public const STATUS_PENDING = 'pending';
    public const STATUS_COMPLETED = 'completed';
    public const STATUS_FAILED = 'failed';
    public const STATUS_CANCELLED = 'cancelled';

    /**
     * Get the portfolio associated with this order
     */
    public function portfolio()
    {
        return $this->belongsTo(Portfolio::class);
    }

    /**
     * Get the user who placed this order
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the payment associated with this order
     */
    public function payment()
    {
        return $this->hasOne(Payment::class);
    }

    /**
     * Get the license associated with this order
     */
    public function license()
    {
        return $this->hasOne(License::class);
    }

    /**
     * Get the billing address associated with this order
     */
    public function billingAddress()
    {
        return $this->belongsTo(BillingAddress::class);
    }

    /**
     * Check if order is completed
     */
    public function isCompleted()
    {
        return $this->status === self::STATUS_COMPLETED;
    }

    /**
     * Mark order as completed and generate license
     */
    public function markAsCompleted($transactionRef = null)
    {
        $this->update([
            'status' => self::STATUS_COMPLETED,
            'transaction_reference' => $transactionRef ?? $this->transaction_reference,
            'completed_at' => now(),
        ]);

        // Generate license for this order
        $license = \App\Services\LicenseService::generateLicense($this);

        // Send completion email with license details
        Mail::to($this->customer_email)->send(new OrderCompletedMail($this));

        // Send license activation instructions email
        Mail::to($this->customer_email)->send(new LicenseActivationMail($license));

        return $this;
    }

    /**
     * Send order confirmation email (when order is created)
     */
    public function sendConfirmationEmail()
    {
        Mail::to($this->customer_email)->send(new OrderConfirmationMail($this));
        return $this;
    }

    /**
     * Scope to get pending orders
     */
    public function scopePending($query)
    {
        return $query->where('status', self::STATUS_PENDING);
    }

    /**
     * Scope to get completed orders
     */
    public function scopeCompleted($query)
    {
        return $query->where('status', self::STATUS_COMPLETED);
    }
}
