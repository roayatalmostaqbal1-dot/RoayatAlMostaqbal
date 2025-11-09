<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserTwoFactorAuth extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'user_two_factor_auth';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'two_factor_enabled',
        'two_factor_secret',
        'two_factor_recovery_codes',
        'two_factor_confirmed_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
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
            'two_factor_enabled' => 'boolean',
            'two_factor_confirmed_at' => 'datetime',
        ];
    }

    /**
     * Get the user that owns this 2FA configuration.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Check if 2FA is enabled for this user.
     */
    public function isEnabled(): bool
    {
        return $this->two_factor_enabled;
    }

    /**
     * Enable 2FA for this user.
     */
    public function enable(): void
    {
        $this->update([
            'two_factor_enabled' => true,
            'two_factor_confirmed_at' => now(),
        ]);
    }

    /**
     * Disable 2FA for this user.
     */
    public function disable(): void
    {
        $this->update([
            'two_factor_enabled' => false,
            'two_factor_secret' => null,
            'two_factor_recovery_codes' => null,
            'two_factor_confirmed_at' => null,
        ]);
    }

    /**
     * Get the decrypted 2FA secret.
     */
    public function getDecryptedSecret(): ?string
    {
        if (!$this->two_factor_secret) {
            return null;
        }
        return decrypt($this->two_factor_secret);
    }

    /**
     * Get the decrypted recovery codes.
     */
    public function getDecryptedRecoveryCodes(): array
    {
        if (!$this->two_factor_recovery_codes) {
            return [];
        }
        return json_decode(decrypt($this->two_factor_recovery_codes), true) ?? [];
    }

    /**
     * Set the encrypted 2FA secret.
     */
    public function setEncryptedSecret(string $secret): void
    {
        $this->update([
            'two_factor_secret' => encrypt($secret),
        ]);
    }

    /**
     * Set the encrypted recovery codes.
     */
    public function setEncryptedRecoveryCodes(array $codes): void
    {
        $this->update([
            'two_factor_recovery_codes' => encrypt(json_encode($codes)),
        ]);
    }
}

