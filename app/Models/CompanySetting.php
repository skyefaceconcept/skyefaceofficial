<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CompanySetting extends Model
{
    protected $fillable = ['logo', 'favicon', 'name_logo', 'show_menu_offer_image', 'company_name', 'cac_number', 'rc_number'];

    protected $casts = [
        'show_menu_offer_image' => 'boolean',
    ];
}
