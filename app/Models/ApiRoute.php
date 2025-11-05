<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Models\Permission;

class ApiRoute extends Model
{
    use HasFactory;

    protected $fillable = [
        'route_name',
        'route_path',
        'http_method',
        'permission_id',
        'description',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the permission required for this route
     */
    public function permission()
    {
        return $this->belongsTo(Permission::class);
    }

    /**
     * Scope to get only active routes
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope to filter by HTTP method
     */
    public function scopeByMethod($query, $method)
    {
        return $query->where('http_method', strtoupper($method));
    }

    /**
     * Scope to filter by route name
     */
    public function scopeByRouteName($query, $routeName)
    {
        return $query->where('route_name', $routeName);
    }

    /**
     * Get the permission for a specific route
     */
    public static function getPermissionForRoute($routeName)
    {
        return cache()->remember(
            "api_route_permission:{$routeName}",
            60 * 60, // 1 hour
            function () use ($routeName) {
                return self::where('route_name', $routeName)
                    ->where('is_active', true)
                    ->with('permission')
                    ->first();
            }
        );
    }

    /**
     * Clear the cache for a specific route
     */
    public static function clearRouteCache($routeName = null)
    {
        if ($routeName) {
            cache()->forget("api_route_permission:{$routeName}");
        } else {
            // Clear all route caches
            cache()->flush();
        }
    }
}

