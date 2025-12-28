<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
use Laravel\Passport\Contracts\OAuthenticatable;
use Laravel\Passport\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements OAuthenticatable
{
    use HasApiTokens, HasFactory, HasRoles ,Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    public $guard_name = 'api';
    protected $keyType = 'string';
    public $incrementing = false;
    protected static function booted()
    {
        static::creating(function ($user) {
            if (empty($user->id)) {
                $user->id = (string) Str::uuid7();
            }
        });
    }
    protected $fillable = [
        'name',
        'email',
        'password',
        'is_active',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
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
    public function socialAccounts()
    {
        return $this->hasMany(SocialAccount::class);
    }

    /**
     * Get the user's two-factor authentication configuration.
     */
    public function twoFactorAuth()
    {
        return $this->hasOne(UserTwoFactorAuth::class);
    }

    /**
     * Check if user has 2FA enabled.
     */
    public function hasTwoFactorEnabled(): bool
    {
        return $this->twoFactorAuth?->two_factor_enabled ?? false;
    }

    /**
     * Get all user permissions through their roles
     * Returns a collection of all unique permissions from all assigned roles
     */
    public function getAllPermissions()
    {
        return $this->roles()
            ->with('permissions')
            ->get()
            ->flatMap(fn($role) => $role->permissions)
            ->unique('id');
    }

    /**
     * Get the user's permissions through their role (attribute accessor)
     * Used for serialization and API responses
     */
    public function getPermissionsAttribute()
    {
        if ($this->relationLoaded('roles') && $this->roles->isEmpty()) {
            return collect();
        }

        return $this->getAllPermissions();
    }

    /**
     * Check if user has a specific permission
     */
    public function hasPermission($permission)
    {
        return $this->getAllPermissions()->contains('name', $permission);
    }

    /**
     * Check if user has any of the given permissions
     */
    public function hasAnyPermission($permissions)
    {
        $userPermissions = $this->getAllPermissions();
        return collect($permissions)->intersect($userPermissions->pluck('name'))->isNotEmpty();
    }

    /**
     * Check if user has all of the given permissions
     */
    public function hasAllPermissions($permissions)
    {
        $userPermissions = $this->getAllPermissions();
        return collect($permissions)->diff($userPermissions->pluck('name'))->isEmpty();
    }

    /**
     * Get user's role names as array
     */
    public function getRoleNames()
    {
        return $this->roles()->pluck('name')->toArray();
    }

    /**
     * Get user's permission names as array
     */
    public function getPermissionNames()
    {
        return $this->getAllPermissions()->pluck('name')->toArray();
    }
}
