<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class GroupResource extends JsonResource
{
    public function toArray(Request $request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'profile_picture' => $this->profile_picture,
            'monitor_id' => $this->monitor_id,
            'sons' => SonResource::collection($this->whenLoaded('sons')),
            'activities' => ActivityResource::collection($this->whenLoaded('activities')),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
