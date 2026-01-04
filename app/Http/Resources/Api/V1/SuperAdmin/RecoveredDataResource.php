<?php

namespace App\Http\Resources\Api\V1\SuperAdmin;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RecoveredDataResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            'data' => $this->resource['decrypted_data'],
            'recovered_at' => $this->resource['recovered_at'],
            'recovered_by' => $this->resource['recovered_by'],
            'data_type' => $this->resource['data_type'] ?? null,
            'user_id' => $this->resource['user_id'] ?? null,
        ];
    }
}
