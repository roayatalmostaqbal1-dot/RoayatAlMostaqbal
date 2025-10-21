<?php

namespace App\Http\Controllers\Api\V1\SuperAdmin;

use App\Http\Controllers\Controller;
use Spatie\Permission\Models\{Role,Permission};
use App\Http\Requests\Api\V1\SuperAdmin\PermissionRole\Permission\{StoreRequest,UpdateRequest};
use App\Http\Resources\Api\V1\SuperAdmin\PermissionRole\{RoleResource,PermissionResource ,PermissionRoleResource};
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
class PermissionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $permissions = Permission::with('roles:id,name')
            ->select(['id', 'name', 'guard_name'])
            ->orderBy('id', 'asc')
            ->paginate(perPage: $request->per_page ?? 10,page: $request->page ?? 1);
    return PermissionResource::collection($permissions);

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRequest $request)
    {
         $permission = Permission::firstOrCreate([
            'name' => $request->name ,
            'guard_name' => 'api',
        ]);
        return new PermissionResource($permission);
    }


    /**
     * Display the specified resource.
     */
    public function show(Permission $permission)
    {
        //

        $permission = Permission::with('roles:id,name')
        ->select(['id', 'name', 'guard_name'])
        ->findOrFail($permission->id);
        return new PermissionResource($permission);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRequest $request, Permission $permission)
    {
        //
        $permission = Permission::findOrFail($permission->id);
        $permission->update([
            'name' => $request->name,
        ]);
        return new PermissionResource($permission);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy( Permission $permission)
    {
        //
        if(!Auth::user()->hasRole('super-admin')||!Auth::user()->hasPermissionTo('delete-permission')){
            return response()->json([
                'response_code' => 403,
                'status' => 'error',
                'message' => 'You are not authorized to delete this permission',
            ], 403);
        }
        $permission = Permission::findOrFail($permission->id);
        $permission->delete();
        return response()->json([
            'response_code' => 200,
            'status' => 'success',
            'message' => 'Permission deleted successfully',
        ]);
    }
}
