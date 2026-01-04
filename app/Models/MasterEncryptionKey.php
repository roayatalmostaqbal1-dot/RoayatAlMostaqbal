<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Log;

class MasterEncryptionKey extends Model
{
    /**
     * Indicates if the IDs are auto-incrementing.
     */
    public $incrementing = false;

    /**
     * The data type of the primary key ID.
     */
    protected $keyType = 'string';

    /**
     * Bootstrap the model.
     */
    protected static function booted(): void
    {
        static::creating(function ($model) {
            if (empty($model->id)) {
                $model->id = (string) Str::uuid7();
            }
            if (empty($model->key_id)) {
                $model->key_id = 'MEK-' . date('Ymd') . '-' . Str::random(8);
            }
        });
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'key_id',
        'encrypted_key',
        'public_key',
        'is_active',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'is_active' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the active master key
     */
    public static function getActiveKey(): ?self
    {
        return self::where('is_active', true)->first();
    }

    /**
     * Get the decrypted master key
     * WARNING: This should only be used in secure server-side contexts
     */
    public function getDecryptedKey(): string
    {
        try {
            return Crypt::decryptString($this->encrypted_key);
        } catch (\Exception $e) {
            Log::error('Failed to decrypt master key: ' . $e->getMessage());
            throw new \RuntimeException('Failed to decrypt master key');
        }
    }

    /**
     * Set the encrypted master key
     */
    public function setEncryptedKey(string $plainKey): void
    {
        $this->encrypted_key = Crypt::encryptString($plainKey);
    }

    /**
     * Generate a new master key
     */
    public static function generateNew(): self
    {
        // Deactivate all existing keys
        self::where('is_active', true)->update(['is_active' => false]);

        // Generate new 32-byte key (256 bits)
        $masterKey = bin2hex(random_bytes(32));

        $mek = new self();
        $mek->setEncryptedKey($masterKey);
        $mek->is_active = true;
        $mek->save();

        return $mek;
    }

    /**
     * Scope to get active keys
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}

