<?php

namespace App\Http\Controllers\Api\V1\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\ApiRoute;
use App\Models\AuditLog;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class ApiRouteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = ApiRoute::with('permission:id,name,group')
            ->select(['id', 'route_name', 'route_path', 'http_method', 'permission_id', 'description', 'is_active']);

        // Filter by active status
        if ($request->has('is_active')) {
            $query->where('is_active', $request->boolean('is_active'));
        }

        // Filter by HTTP method
        if ($request->has('http_method')) {
            $query->where('http_method', strtoupper($request->http_method));
        }

        // Search by route name or path
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('route_name', 'like', "%{$search}%")
                    ->orWhere('route_path', 'like', "%{$search}%");
            });
        }

        $routes = $query->orderBy('route_name')->paginate($request->per_page ?? 15);

        return response()->json([
            'data' => $routes->items(),
            'pagination' => [
                'total' => $routes->total(),
                'per_page' => $routes->perPage(),
                'current_page' => $routes->currentPage(),
                'last_page' => $routes->lastPage(),
            ],
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'route_name' => 'required|string|unique:api_routes,route_name',
            'route_path' => 'required|string',
            'http_method' => 'required|in:GET,POST,PUT,DELETE,PATCH',
            'permission_id' => 'nullable|exists:permissions,id',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        $apiRoute = ApiRoute::create($validated);

        // Log the action
        AuditLog::create([
            'user_id' => Auth::id(),
            'model_type' => 'ApiRoute',
            'model_id' => $apiRoute->id,
            'action' => 'created',
            'new_values' => $apiRoute->toArray(),
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        // Clear cache
        ApiRoute::clearRouteCache($apiRoute->route_name);

        return response()->json([
            'data' => $apiRoute->load('permission:id,name,group'),
            'message' => 'API route created successfully',
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(ApiRoute $apiRoute)
    {
        return response()->json([
            'data' => $apiRoute->load('permission:id,name,group'),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ApiRoute $apiRoute)
    {
        $validated = $request->validate([
            'route_name' => 'sometimes|string|unique:api_routes,route_name,' . $apiRoute->id,
            'route_path' => 'sometimes|string',
            'http_method' => 'sometimes|in:GET,POST,PUT,DELETE,PATCH',
            'permission_id' => 'nullable|exists:permissions,id',
            'description' => 'nullable|string',
            'is_active' => 'sometimes|boolean',
        ]);

        $oldValues = $apiRoute->toArray();
        $apiRoute->update($validated);

        // Log the action
        AuditLog::create([
            'user_id' => Auth::id(),
            'model_type' => 'ApiRoute',
            'model_id' => $apiRoute->id,
            'action' => 'updated',
            'old_values' => $oldValues,
            'new_values' => $apiRoute->toArray(),
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        // Clear cache
        ApiRoute::clearRouteCache($apiRoute->route_name);

        return response()->json([
            'data' => $apiRoute->load('permission:id,name,group'),
            'message' => 'API route updated successfully',
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ApiRoute $apiRoute, Request $request)
    {
        $routeData = $apiRoute->toArray();
        $routeName = $apiRoute->route_name;

        $apiRoute->delete();

        // Log the action
        AuditLog::create([
            'user_id' => Auth::id(),
            'model_type' => 'ApiRoute',
            'model_id' => $apiRoute->id,
            'action' => 'deleted',
            'old_values' => $routeData,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        // Clear cache
        ApiRoute::clearRouteCache($routeName);

        return response()->json([
            'message' => 'API route deleted successfully',
        ]);
    }
}

