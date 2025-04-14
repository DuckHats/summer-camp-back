<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ActivityResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'cover_image' => $this->cover_image,
            'scheduled_activities' => $this->whenLoaded('scheduledActivities', fn () => ScheduledActivityResource::collection($this->scheduledActivities ?? collect())
            ),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
