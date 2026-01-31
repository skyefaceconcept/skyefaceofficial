<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmailDeliveryTest extends Model
{
    protected $fillable = [
        'provider',
        'test_email',
        'status',
        'error_message',
        'response_code',
        'last_tested_at',
    ];

    protected $casts = [
        'last_tested_at' => 'datetime',
    ];
}
