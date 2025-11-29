<?php

namespace App\Http\Resources\Api\V1\User;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\User;

class UserInfoResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request)
    {
        // Load user with roles and permissions relationships
        $user = User::with(['roles.permissions'])->findOrFail($this->id);

        $only_user_data = ['id', 'name', 'email'];
        $accessToken = $this->additional['token'] ?? null;

        // Get all unique permissions from user's roles
        $permissions = $user->roles
            ->flatMap(fn($role) => $role->permissions)
            ->unique('id')
            ->pluck('name')
            ->values();

        return [
            'response_code' => 200,
            'status'        => 'success',
            'message'       => 'Login successful',
            'data'          => [
                'user_info'     => $user->only($only_user_data),
                'roles'         => $user->roles->pluck('name')->values(),
                'permissions'   => $permissions,
            ],
        ];
    }
}
