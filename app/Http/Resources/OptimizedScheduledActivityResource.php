<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OptimizedScheduledActivityResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'activity' => [
                'id' => $this->activity->id,
                'name' => $this->activity->name,
                'description' => $this->activity->description,
                'cover_image' => $this->activity->cover_image
            ],
            'group' => $this->group ? [
                'id' => $this->group->id,
                'name' => $this->group->name,
                'monitor' => $this->group->monitor ? [
                    'id' => $this->group->monitor->id,
                    'name' => $this->group->monitor->first_name . ' ' . $this->group->monitor->last_name
                ] : null,
                'childs' => $this->group->childs->map(function($child) {
                    return [
                        'id' => $child->id,
                        'name' => $child->user->first_name . ' ' . $child->user->last_name,
                        'profile_picture' => $child->profile_picture_url
                    ];
                })
            ] : null,
            'schedule' => [
                'initial_hour' => $this->initial_hour,
                'final_hour' => $this->final_hour,
                'location' => $this->location
            ]
        ];
    }
} 