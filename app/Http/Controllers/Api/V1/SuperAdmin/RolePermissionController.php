<?php

namespace App\Http\Controllers\Api\V1\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class RolePermissionController extends Controller
{
    /**
     * Get all permissions for a specific role
     */
    public function getPermissions(Role $role)
    {
        $permissions = $role->permissions()
            ->select(['id', 'name', 'description', 'group'])
            ->get()
            ->groupBy('group');

        return response()->json([
            'data' => $permissions,
            'role_id' => $role->id,
            'role_name' => $role->name,
        ]);
    }

    /**
     * Assign permissions to a role
     */
    public function assignPermissions(Request $request, Role $role)
    {
        $validated = $request->validate([
            'permission_ids' => 'required|array',
            'permission_ids.*' => 'exists:permissions,id',
        ]);

        $oldPermissions = $role->permissions()->pluck('id')->toArray();

        // Sync permissions (this will replace all existing permissions)
        $role->syncPermissions($validated['permission_ids']);

        $newPermissions = $role->permissions()->pluck('id')->toArray();

        // Log the action
        AuditLog::create([
            'user_id' => Auth::id(),
            'model_type' => 'Role',
            'model_id' => $role->id,
            'action' => 'updated',
            'old_values' => ['permissions' => $oldPermissions],
            'new_values' => ['permissions' => $newPermissions],
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return response()->json([
            'message' => 'Permissions assigned successfully',
            'data' => $role->permissions()->select(['id', 'name', 'description', 'group'])->get(),
        ]);
    }

    /**
     * Add a single permission to a role
     */
    public function grantPermission(Request $request, Role $role)
    {
        $validated = $request->validate([
            'permission_id' => 'required|exists:permissions,id',
        ]);

        $permission = Permission::findOrFail($validated['permission_id']);

        if (!$role->hasPermissionTo($permission)) {
            $role->givePermissionTo($permission);

            // Log the action
            AuditLog::create([
                'user_id' => Auth::id(),
                'model_type' => 'Role',
                'model_id' => $role->id,
                'action' => 'updated',
                'new_values' => ['permission_granted' => $permission->name],
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);
        }

        return response()->json([
            'message' => 'Permission granted successfully',
            'data' => $role->permissions()->select(['id', 'name', 'description', 'group'])->get(),
        ]);
    }

    /**
     * Remove a permission from a role
     */
    public function revokePermission(Request $request, Role $role)
    {
        $validated = $request->validate([
            'permission_id' => 'required|exists:permissions,id',
        ]);

        $permission = Permission::findOrFail($validated['permission_id']);

        if ($role->hasPermissionTo($permission)) {
            $role->revokePermissionTo($permission);

            // Log the action
            AuditLog::create([
                'user_id' => Auth::id(),
                'model_type' => 'Role',
                'model_id' => $role->id,
                'action' => 'updated',
                'new_values' => ['permission_revoked' => $permission->name],
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);
        }

        return response()->json([
            'message' => 'Permission revoked successfully',
            'data' => $role->permissions()->select(['id', 'name', 'description', 'group'])->get(),
        ]);
    }

    /**
     * Get all available permissions grouped by category
     */
    public function getAllPermissions()
    {
        $permissions = Permission::select(['id', 'name', 'description', 'group'])
            ->orderBy('group')
            ->orderBy('name')
            ->get()
            ->groupBy('group');

        return response()->json([
            'data' => $permissions,
        ]);
    }
}

