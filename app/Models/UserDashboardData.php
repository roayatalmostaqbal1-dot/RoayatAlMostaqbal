<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserDashboardData extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'user_dashboard_data';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'digital_identity_status',
        'identity_number',
        'nationality',
        'security_level',
        'uae_pass_connected',
        'last_sync_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'uae_pass_connected' => 'boolean',
        'last_sync_at' => 'datetime',
    ];

    /**
     * Get the user that owns the dashboard data.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get formatted last sync time.
     */
    public function getFormattedLastSyncAttribute(): ?string
    {
        return $this->last_sync_at?->format('Y-m-d H:i');
    }

    /**
     * Check if identity is active.
     */
    public function isIdentityActive(): bool
    {
        return $this->digital_identity_status === 'active';
    }
}
