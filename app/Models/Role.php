<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Role extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
    ];

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function permissions()
    {
        return $this->belongsToMany(Permission::class, 'role_permission');
    }

    /**
     * Use slug for route model binding.
     */
    public function getRouteKeyName()
    {
        return 'slug';
    }

    /**
     * Ensure a slug is present and unique when creating.
     */
    protected static function booted()
    {
        static::creating(function ($role) {
            if (empty($role->slug) && ! empty($role->name)) {
                $base = Str::slug($role->name);
            } else {
                $base = Str::slug($role->slug ?? $role->name ?? 'role');
            }

            $slug = $base;
            $i = 1;
            while (static::where('slug', $slug)->exists()) {
                $slug = $base . '-' . $i++;
            }

            $role->slug = $slug;
        });

        static::updating(function ($role) {
            // If slug is empty ensure it's generated from name
            if (empty($role->slug) && ! empty($role->name)) {
                $base = Str::slug($role->name);
                $slug = $base;
                $i = 1;
                while (static::where('slug', $slug)->where('id', '!=', $role->id)->exists()) {
                    $slug = $base . '-' . $i++;
                }
                $role->slug = $slug;
            }
        });
    }
}
