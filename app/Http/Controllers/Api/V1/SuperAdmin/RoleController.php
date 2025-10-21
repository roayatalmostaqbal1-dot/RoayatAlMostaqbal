<?php

namespace App\Http\Controllers\Api\V1\SuperAdmin;

use App\Http\Controllers\Controller;
use Spatie\Permission\Models\{Role,Permission};
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Resources\Api\V1\SuperAdmin\PermissionRole\{RoleResource,PermissionResource ,PermissionRoleResource};
use App\Http\Requests\Api\V1\SuperAdmin\PermissionRole\Role\{StoreRequest,UpdateRequest};
class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     * Api\V1\Admin\PermissionRole\PermissionRole\AddpermissionToRoleRequest
     * Api\V1\Admin\PermissionRole\PermissionRole\RemovepermissionToRoleRequest
     */
    public function index(Request $request)
    {
        $roles = Role::with('permissions:id,name')
            ->select(['id', 'name', 'guard_name'])
            ->orderBy('id', 'asc')
            ->paginate(perPage: $request->per_page ?? 10,page: $request->page ?? 1);

        return RoleResource::collection($roles);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRequest $request)
    {
        //
        $role = Role::firstOrCreate([
            'name' => $request->name,
            'guard_name' => 'api',
        ]);
        return new RoleResource($role);
    }

    /**
     * Display the specified resource.
     */
    public function show(Role $role)
    {
        //
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
        //
        $role = Role::findOrFail($id);
        $role->update([
            'name' => $request->name,
        ]);
        return new RoleResource($role);

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Role $role)
    {
        if(!Auth::user()->hasRole('super-admin')||!Auth::user()->hasPermissionTo('delete-role')){
            return response()->json([
                'response_code' => 403,
                'status' => 'error',
                'message' => 'You are not authorized to delete this role',
            ], 403);
        }
        $role = Role::findOrFail($role->id);
        $role->delete();
        return response()->json([
            'response_code' => 200,
            'status' => 'success',
            'message' => 'Role deleted successfully',
        ]);
    }

}
