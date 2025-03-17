<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PhoneVerificationResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'phone' => $this->phone,
            'verification_code' => $this->verification_code,
            'expires_at' => $this->expires_at,
        ];
    }
}
