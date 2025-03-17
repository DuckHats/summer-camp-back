<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class EmailVerificationResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'email' => $this->email,
            'verification_code' => $this->verification_code,
            'expires_at' => $this->expires_at,
        ];
    }
}
