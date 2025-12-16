<?php

namespace App\Http\Controllers\Api\V1\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Resources\Api\V1\SuperAdmin\PermissionRole\{RoleResource, PermissionResource, PermissionRoleResource};
use App\Http\Requests\Api\V1\SuperAdmin\PermissionRole\Role\{StoreRequest, UpdateRequest};

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $roles = Role::with('permissions:id,name')
            ->select(['id', 'name', 'guard_name'])
            ->orderBy('id', 'asc')
            ->paginate(perPage: $request->per_page ?? 10, page: $request->page ?? 1);

        return RoleResource::collection($roles);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRequest $request)
    {
        $role = Role::firstOrCreate([
            'name' => $request->name,
            'guard_name' => 'api',
        ]);

        // Log the action
        AuditLog::create([
            'user_id' => Auth::id(),
            'model_type' => 'Role',
            'model_id' => $role->id,
            'action' => 'created',
            'new_values' => $role->toArray(),
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return new RoleResource($role);
    }

    /**
     * Display the specified resource.
     */
    public function show(Role $role)
    {
        $role = Role::with('permissions:id,name')
            ->select(['id', 'name', 'guard_name'])
            ->findOrFail($role->id);

        return new RoleResource($role);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRequest $request, string $id)
    {
        $role = Role::findOrFail($id);
        $oldValues = $role->toArray();

        $role->update([
            'name' => $request->name,
        ]);

        // Log the action
        AuditLog::create([
            'user_id' => Auth::id(),
            'model_type' => 'Role',
            'model_id' => $role->id,
            'action' => 'updated',
            'old_values' => $oldValues,
            'new_values' => $role->toArray(),
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return new RoleResource($role);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Role $role, Request $request)
    {
        // Check if role has users assigned
        if ($role->users()->count() > 0) {
            return response()->json([
                'response_code' => 422,
                'status' => 'error',
                'message' => 'Cannot delete role with assigned users',
            ], 422);
        }

        $roleData = $role->toArray();
        $role->delete();

        // Log the action
        AuditLog::create([
            'user_id' => Auth::id(),
            'model_type' => 'Role',
            'model_id' => $role->id,
            'action' => 'deleted',
            'old_values' => $roleData,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return response()->json([
            'response_code' => 200,
            'status' => 'success',
            'message' => 'Role deleted successfully',
        ]);
    }
}
