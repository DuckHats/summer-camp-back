<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserLastLoginResource extends JsonResource
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
            'user_id' => $this->user_id,
            'ip_address' => $this->ip_address,
            'user_agent' => $this->user_agent,
            'country' => $this->country,
            'city' => $this->city,
            'zip_code' => $this->zip_code,
            'last_login_at' => $this->last_login_at,
        ];
    }
}
