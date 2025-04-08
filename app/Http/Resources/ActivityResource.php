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
            'initial_hour' => $this->initial_hour,
            'final_hour' => $this->final_hour,
            'duration' => $this->duration,
            'description' => $this->description,
            'cover_image' => $this->cover_image,
            'location' => $this->location,
            'scheduled_activities' => ScheduledActivityResource::collection($this->whenLoaded('scheduledActivities')),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
