<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ChildResource extends JsonResource
{
    private $fullDetails;

    public function __construct($resource, $fullDetails = false)
    {
        parent::__construct($resource);
        $this->fullDetails = $fullDetails;
    }

    public function toArray($request)
    {
        $data = [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'dni' => $this->dni,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'birth_date' => $this->birth_date,
            'group_id' => $this->group_id,
            'profile_picture_url' => $this->profile_picture_url,
            'profile_extra_info' => $this->profile_extra_info,
            'gender' => $this->gender,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];

        if ($this->fullDetails) {
            $data['user'] = $this->whenLoaded('user', fn () => new UserResource($this->user));
            $data['group'] = $this->whenLoaded('group', fn () => new GroupResource($this->group));
            $data['scheduledActivities'] = $this->whenLoaded('scheduledActivities', fn () => ScheduledActivityResource::collection($this->scheduledActivities ?? collect())
            );
            $data['photos'] = optional($this->group)?->relationLoaded('photos')
                ? PhotoResource::collection($this->group->photos ?? collect())
                : null;
        }

        return $data;
    }
}
