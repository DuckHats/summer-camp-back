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
            'monitor' => new MonitorResource($this->whenLoaded('monitor')),
            'childs' => ChildResource::collection($this->whenLoaded('childs')),
            'scheduledActivities' => ScheduledActivityResource::collection($this->whenLoaded('scheduledActivities')),
            'photos' => PhotoResource::collection($this->whenLoaded('photos')),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
