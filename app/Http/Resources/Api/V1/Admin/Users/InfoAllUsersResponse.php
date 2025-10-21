<?php

namespace App\Http\Resources\Api\V1\Admin\Users;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class InfoAllUsersResponse extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $this->collection->transform(function ($user) {
            return [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'roles' => $user->roles->pluck('name'),
            ];
        });
        return [
            'response_code' => 200,
            'status'        => 'success',
            'message'       => 'Fetched user list successfully',
            'data_user_list'=> $this->collection,
        ];
          
    }
}
