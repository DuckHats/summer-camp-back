<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ScheduledActivityResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'initial_date' => $this->initial_date,
            'final_date' => $this->final_date,
            'initial_hour' => $this->initial_hour,
            'final_hour' => $this->final_hour,
            'location' => $this->location,
        ];
    }
}
