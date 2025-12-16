<?php

namespace App\Http\Controllers\Api\V1\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Role;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\Api\V1\SuperAdmin\PermissionRole\{RoleResource,PermissionResource ,PermissionRoleResource};
use App\Http\Requests\Api\V1\SuperAdmin\PermissionRole\PermissionRole\{AddpermissionToRoleRequest,RemovepermissionToRoleRequest};

class PermissionRoleController extends Controller
{
    public function index(Request $request)
    {
        $request->validate([
            'type' => 'required|in:role,permission',
        ]);
        if($request->type == 'role'){
            $permissionRole = Role::with('permissions:id,name')
            ->select(['id', 'name', 'guard_name'])
            ->paginate(perPage: $request->per_page ?? 10,page: $request->page ?? 1);
            return RoleResource::collection($permissionRole);
        }
        if($request->type == 'permission'){
        $permissionRole = Permission::with('roles:id,name')
            ->select(['id', 'name', 'guard_name'])
            ->paginate(perPage: $request->per_page ?? 10,page: $request->page ?? 1);
        return PermissionResource::collection($permissionRole);
        }
        return response()->json([
            'response_code' => 400,
            'status' => 'error',
            'message' => 'Invalid type',
        ], 400);
    }
    public function store(AddpermissionToRoleRequest $request)
    {
        $role = Role::findOrFail($request->role_id);
        $permission = Permission::findOrFail($request->permission_id);

        if ($request->type == 'role') {
            $role->givePermissionTo($permission);
            return new RoleResource($role);
        }
        $permission->assignRole($role);
        return new PermissionResource($permission);
    }
    public function show(Request $request, string $permissionRole)
    {
        $request->validate([
            'type' => 'required|in:role,permission',
        ]);
        if($request->type == 'role'){
            $permissionRole = Role::with('permissions:id,name')
                ->select(['id', 'name', 'guard_name'])
                ->findOrFail($permissionRole);
            return new RoleResource($permissionRole);
        }
        $permissionRole = Permission::with('roles:id,name')
            ->select(['id', 'name', 'guard_name'])
            ->findOrFail($permissionRole);
        return new PermissionResource($permissionRole);
    }
    public function update(RemovepermissionToRoleRequest $request, string $permissionRole)
    {
        $role = Role::findOrFail($permissionRole);
        $permission = Permission::findOrFail($request->permission_id);
        if ($request->type == 'role') {
            $role->revokePermissionTo($permission);
            return new RoleResource($role);
        }
        $permission->removeRole($role);
        return new PermissionResource($permission);
    }
    public function destroy(Request $request, string $permissionRole)
    {
        $request->validate([
            'type' => 'required|in:role,permission',
            'role_id' => 'required|exists:roles,id',
            'permission_id' => 'required|exists:permissions,id',
        ]);
        $permission = Permission::findOrFail($request->permission_id);
        $role = Role::findOrFail($request->role_id);
        if ($request->type == 'role'){
            $role->revokePermissionTo($permission);
            return new RoleResource($role);
        }
        $permission->removeRole($role);
        return new PermissionResource($permission);
    }
}
