<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ScheduledActivityResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'activity_id' => $this->activity_id,
            'activity' => $this->whenLoaded('activity', fn() => new ActivityResource($this->activity)),
            'group_id' => $this->group_id,
            'group' => $this->whenLoaded('group', fn() => new GroupResource($this->group)),
            'initial_date' => $this->initial_date,
            'final_date' => $this->final_date,
            'initial_hour' => $this->initial_hour,
            'final_hour' => $this->final_hour,
            'location' => $this->location,
        ];
    }
}
