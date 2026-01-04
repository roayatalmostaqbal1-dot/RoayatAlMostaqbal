<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Admin\Users\StoreUserRequest;
use App\Http\Resources\Api\V1\Admin\Users\InfoAllUsersResponse;
use App\Http\Resources\Api\V1\User\UserInfoResource;
use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     *  Api\V1\Admin\Users\Admin\PermissionRole\
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->query('search');
        $users = User::with('roles')
            ->select(['id', 'name', 'email', 'is_active'])
            ->whereAny(["name", "email"], "like", "%{$search}%")
            ->paginate(perPage: $request->per_page ?? 10, page: $request->page ?? 1);
        return new InfoAllUsersResponse($users);
    }

    public function getRoles()
    {
        $roles = Role::select(['id', 'name'])->get();

        return response()->json([
            'response_code' => 200,
            'status' => 'success',
            'message' => 'Fetched roles successfully',
            'data' => $roles,
        ]);
    }

    public function store(StoreUserRequest $request)
    {
        try {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => bcrypt($request->password),
            ]);
            $user->assignRole($request->role ?? 'user');
            return new UserInfoResource($user);
        } catch (\Exception $e) {
            return response()->json([
                'response_code' => 500,
                'status' => 'error',
                'message' => 'Registration failed',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        // Load roles relationship
        $user->load('roles');
        return new UserInfoResource($user);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        try {
            $updateData = [
                'name' => $request->name,
                'email' => $request->email,
                'is_active' => $request->is_active,
            ];

            // Only update password if provided
            if ($request->filled('password')) {
                $updateData['password'] = bcrypt($request->password);
            }

            $user->update($updateData);
            $user->syncRoles($request->role ?? 'user');
            $user->save();

            // Reload with roles
            $user->load('roles');

            return new UserInfoResource($user);
        } catch (\Exception $e) {
            return response()->json([
                'response_code' => 500,
                'status' => 'error',
                'message' => 'Update failed',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        try {
            $user->delete();

            return response()->json([
                'response_code' => 200,
                'status' => 'success',
                'message' => 'User deleted successfully',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'response_code' => 500,
                'status' => 'error',
                'message' => 'Delete failed',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
