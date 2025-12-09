<?php

namespace App\Http\Resources\Api\V1\SuperAdmin\OAuth2Client;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OAuth2ClientResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'client_id' => $this->id,
            'client_secret' => $this->secret,
            'redirect_uris' => $this->redirect_uris,
            'confidential' => !is_null($this->secret),
            'revoked' => (bool) $this->revoked,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}

