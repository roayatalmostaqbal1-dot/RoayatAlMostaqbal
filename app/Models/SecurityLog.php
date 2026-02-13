<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Builder;
use Carbon\Carbon;

class SecurityLog extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'event_type',
        'source',
        'severity',
        'description',
        'metadata',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'metadata' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the user that owns the security log.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope a query to only include logs of a specific severity.
     */
    public function scopeOfSeverity(Builder $query, string $severity): Builder
    {
        return $query->where('severity', $severity);
    }

    /**
     * Scope a query to only include logs of a specific event type.
     */
    public function scopeOfEventType(Builder $query, string $eventType): Builder
    {
        return $query->where('event_type', $eventType);
    }

    /**
     * Scope a query to only include logs within a date range.
     */
    public function scopeDateRange(Builder $query, Carbon $startDate, Carbon $endDate): Builder
    {
        return $query->whereBetween('created_at', [$startDate, $endDate]);
    }

    /**
     * Scope a query to only include logs from today.
     */
    public function scopeToday(Builder $query): Builder
    {
        return $query->whereDate('created_at', Carbon::today());
    }

    /**
     * Scope a query to only include logs from the last week.
     */
    public function scopeLastWeek(Builder $query): Builder
    {
        return $query->where('created_at', '>=', Carbon::now()->subWeek());
    }

    /**
     * Scope a query to only include logs from the last month.
     */
    public function scopeLastMonth(Builder $query): Builder
    {
        return $query->where('created_at', '>=', Carbon::now()->subMonth());
    }

    /**
     * Get formatted timestamp for display.
     */
    public function getFormattedTimestampAttribute(): string
    {
        return $this->created_at->format('Y-m-d H:i:s');
    }

    /**
     * Get severity badge color.
     */
    public function getSeverityColorAttribute(): string
    {
        return match($this->severity) {
            'low' => 'green',
            'medium' => 'yellow',
            'high' => 'orange',
            'critical' => 'red',
            default => 'gray',
        };
    }
}
