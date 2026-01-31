<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class BillingAddress extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'email',
        'customer_name',
        'customer_email',
        'customer_phone',
        'address',
        'city',
        'state',
        'zip',
        'country',
        'is_default',
    ];

    protected $casts = [
        'is_default' => 'boolean',
    ];

    /**
     * Get the user associated with this billing address
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the orders associated with this billing address
     */
    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    /**
     * Get full address as a string
     */
    public function getFullAddressAttribute()
    {
        return "{$this->address}, {$this->city}, {$this->state} {$this->zip}, {$this->country}";
    }

    /**
     * Find a saved address by customer email (for reuse)
     */
    public static function findByEmail($email)
    {
        return self::where('customer_email', $email)->first();
    }
}

