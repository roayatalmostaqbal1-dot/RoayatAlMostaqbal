<?php

namespace App\Http\Middleware;

use App\Models\ApiRoute;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class CheckApiPermission
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Skip permission check for unauthenticated requests
        if (!Auth::check()) {
            return $next($request);
        }

        $user = Auth::user();

        // Super Admin bypass - check if user has super-admin role
        if ($user->hasRole('super-admin')) {
            return $next($request);
        }

        // Get the current route name
        $routeName = $request->route()?->getName();

        if (!$routeName) {
            return $next($request);
        }

        // Get the required permission for this route
        $apiRoute = ApiRoute::getPermissionForRoute($routeName);

        // If no permission is required, allow the request
        if (!$apiRoute || !$apiRoute->permission) {
            return $next($request);
        }

        // Check if user has the required permission
        if (!$user->hasPermissionTo($apiRoute->permission->name)) {
            return response()->json([
                'message' => 'Unauthorized - Permission denied',
                'permission_required' => $apiRoute->permission->name,
            ], 403);
        }

        return $next($request);
    }
}

