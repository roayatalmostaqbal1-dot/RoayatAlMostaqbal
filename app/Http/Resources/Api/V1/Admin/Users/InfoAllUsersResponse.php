<?php

namespace App\Http\Resources\Api\V1\Admin\Users;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class InfoAllUsersResponse extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'response_code' => 200,
            'status'        => 'success',
            'message'       => 'Fetched user list successfully',
            'data'          => $this->collection->map(function ($user) {
                return [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'roles' => $user->roles->pluck('name'),
                    'is_active' => $user->is_active ?? true,
                ];
            }),
            'meta' => [
                'current_page' => $this->currentPage(),
                'per_page' => $this->perPage(),
                'total' => $this->total(),
                'last_page' => $this->lastPage(),
            ],
        ];
    }
}
