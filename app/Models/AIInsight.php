<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AIInsight extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'ai_insights';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'risk_level',
        'risk_score',
        'recommendation',
        'threat_indicators',
        'generated_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'threat_indicators' => 'array',
        'generated_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the user that owns the AI insight.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope a query to only include current insights (from last 24 hours).
     */
    public function scopeCurrent(Builder $query): Builder
    {
        return $query->where('generated_at', '>=', Carbon::now()->subDay());
    }

    /**
     * Scope a query to only include insights of a specific risk level.
     */
    public function scopeOfRiskLevel(Builder $query, string $riskLevel): Builder
    {
        return $query->where('risk_level', $riskLevel);
    }

    /**
     * Scope a query to get the latest insight for a user.
     */
    public function scopeLatestForUser(Builder $query, string $userId): Builder
    {
        return $query->where('user_id', $userId)
            ->orderBy('generated_at', 'desc')
            ->limit(1);
    }

    /**
     * Get risk level percentage for display.
     */
    public function getRiskPercentageAttribute(): int
    {
        return match ($this->risk_level) {
            'low' => 25,
            'medium' => 50,
            'high' => 75,
            'critical' => 100,
            default => 0,
        };
    }

    /**
     * Get risk level color for UI.
     */
    public function getRiskColorAttribute(): string
    {
        return match ($this->risk_level) {
            'low' => 'green',
            'medium' => 'yellow',
            'high' => 'orange',
            'critical' => 'red',
            default => 'gray',
        };
    }
}
