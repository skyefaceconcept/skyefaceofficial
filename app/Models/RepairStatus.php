<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RepairStatus extends Model
{
    use HasFactory;

    protected $table = 'repair_statuses';

    protected $fillable = [
        'repair_id',
        'status',
        'description',
        'notes',
        'stage',
        'estimated_completion',
        'updated_by',
    ];

    protected $casts = [
        'estimated_completion' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the repair this status belongs to
     */
    public function repair()
    {
        return $this->belongsTo(Repair::class);
    }
}
