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
        $user = User::findOrFail($this->id);
        $only_user_data = ['id', 'name', 'email'];
        $accessToken =$this->additional['token'] ?? null;
        return [
            'response_code' => 200,
            'status'        => 'success',
            'message'       => 'Login successful',
            'user_info'     => $user->only($only_user_data),
            'role_name'       => $user->roles->pluck('name'),
         ];

 

    }
}
