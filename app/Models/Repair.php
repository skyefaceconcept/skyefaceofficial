<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Repair extends Model
{
    use HasFactory;

    protected $table = 'repairs';

    protected $fillable = [
        'invoice_number',
        'customer_name',
        'customer_email',
        'customer_phone',
        'device_type',
        'device_brand',
        'device_model',
        'issue_description',
        'urgency',
        'status',
        'estimated_completion',
        'completed_at',
        'cost_estimate',
        'cost_actual',
        'notes',
        'payment_status',
        'payment_verified_at',
        'payment_reference',
        'payment_processor',
        'payment_id',
    ];

    protected $casts = [
        'completed_at' => 'datetime',
        'estimated_completion' => 'datetime',
        'payment_verified_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get all status updates for this repair
     */
    public function statusUpdates()
    {
        return $this->hasMany(RepairStatus::class);
    }

    /**
     * Get the latest status update
     */
    public function latestStatus()
    {
        return $this->hasOne(RepairStatus::class)->latest();
    }
}
