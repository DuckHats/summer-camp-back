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
            'monitor' => $this->whenLoaded('monitor', fn () => new MonitorResource($this->monitor)),
            'childs' => $this->whenLoaded('childs', fn () => ChildResource::collection($this->childs ?? collect())
            ),
            'scheduledActivities' => $this->whenLoaded('scheduledActivities', fn () => ScheduledActivityResource::collection($this->scheduledActivities ?? collect())
            ),
            'photos' => $this->whenLoaded('photos', fn () => PhotoResource::collection($this->photos ?? collect())
            ),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
