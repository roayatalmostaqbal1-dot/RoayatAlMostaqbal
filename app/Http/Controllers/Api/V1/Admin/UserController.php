<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Requests\Api\V1\Admin\Users\StoreUserRequest;
use App\Http\Resources\Api\V1\Admin\Users\InfoAllUsersResponse;
use App\Http\Resources\Api\V1\User\UserInfoResource;

use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     *  Api\V1\Admin\Users\Admin\PermissionRole\
     * Display a listing of the resource.
     *
     */
    public function index()
    {
       $users = User::with('roles')
        ->select(['id', 'name', 'email'])
        ->paginate(2);

    return new InfoAllUsersResponse($users);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUserRequest $request)
    {
        //
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
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        //
    }
}
