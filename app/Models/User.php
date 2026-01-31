<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens;

    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory;
    use HasProfilePhoto;
    use Notifiable;
    use TwoFactorAuthenticatable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'fname',
        'mname',
        'lname',
        'dob',
        'phone',
        'username',
        'email',
        'password',
        'role_id',
        'profile_photo_path',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array<int, string>
     */
    protected $appends = [
        'profile_photo_url',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Get the user's role.
     */
    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    /**
     * Check if the user has a specific role.
     *
     * @param string|array $roles
     * @return bool
     */
    public function hasRole($roles)
    {
        if (is_string($roles)) {
            return $this->role && $this->role->slug === $roles;
        }

        if (is_array($roles)) {
            return $this->role && in_array($this->role->slug, $roles);
        }

        return false;
    }

    /**
     * Check if the user has a specific permission via their role.
     *
     * @param string|array $permissions
     * @return bool
     */
    public function hasPermission($permissions)
    {
        $perms = is_array($permissions) ? $permissions : [$permissions];

        if (! $this->role) {
            return false;
        }

        $rolePerms = $this->role->permissions->pluck('slug')->toArray();

        foreach ($perms as $p) {
            if (in_array($p, $rolePerms)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Alias for checking any permission in array.
     */
    public function hasAnyPermission(array $permissions): bool
    {
        return $this->hasPermission($permissions);
    }

    /**
     * Check if the user is a superadmin.
     *
     * @return bool
     */
    public function isSuperAdmin(): bool
    {
        return $this->role && $this->role->slug === 'superadmin';
    }

    /**
     * Check if the user is an admin (superadmin or admin role).
     *
     * @return bool
     */
    public function isAdmin(): bool
    {
        return $this->role && in_array($this->role->slug, ['superadmin', 'admin']);
    }
}
