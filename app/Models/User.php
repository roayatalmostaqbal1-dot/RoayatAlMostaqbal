<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
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
    protected $fillable = [
        'name',
        'email',
        'password',
        'two_factor_enabled',
        'two_factor_secret',
        'two_factor_recovery_codes',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_secret',
        'two_factor_recovery_codes',
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
     * Get the user's permissions through their role
     */
    public function getPermissionsAttribute()
    {
        if ($this->roles->isEmpty()) {
            return collect();
        }

        return $this->roles->first()->permissions ?? collect();
    }

    /**
     * Check if user has a specific permission
     */
    public function hasPermission($permission)
    {
        return $this->roles->first()?->permissions->contains('name', $permission) ?? false;
    }

    /**
     * Check if user has any of the given permissions
     */
    public function hasAnyPermission($permissions)
    {
        $userPermissions = $this->getPermissionsAttribute();
        return collect($permissions)->intersect($userPermissions->pluck('name'))->isNotEmpty();
    }

    /**
     * Check if user has all of the given permissions
     */
    public function hasAllPermissions($permissions)
    {
        $userPermissions = $this->getPermissionsAttribute();
        return collect($permissions)->diff($userPermissions->pluck('name'))->isEmpty();
    }
}
