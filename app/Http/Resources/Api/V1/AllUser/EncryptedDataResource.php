<?php

namespace App\Http\Resources\Api\V1\AllUser;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EncryptedDataResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'encrypted_dek' => $this->encrypted_dek,
            'encrypted_dek_recovery' => $this->encrypted_dek_recovery,
            'dek_salt' => $this->dek_salt,
            'dek_nonce' => $this->dek_nonce,
            'dek_salt_recovery' => $this->dek_salt_recovery,
            'dek_nonce_recovery' => $this->dek_nonce_recovery,
            'profile_ciphertext' => $this->profile_ciphertext,
            'profile_nonce' => $this->profile_nonce,
            'data_type' => $this->data_type,
            'metadata' => $this->metadata,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
