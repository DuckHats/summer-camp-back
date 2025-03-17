<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PolicyResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'accept_newsletter' => $this->accept_newsletter,
            'accept_privacy_policy' => $this->accept_privacy_policy,
            'accept_terms_of_use' => $this->accept_terms_of_use,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
