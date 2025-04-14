<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
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
            'username' => $this->username,
            'status' => $this->status,
            'email' => $this->email,
            'phone' => $this->phone,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'user_settings' => $this->whenLoaded('settings', fn () => UserSettingResource::collection($this->settings ?? collect())
            ),
            'user_policies' => $this->whenLoaded('policies', fn () => PolicyResource::collection($this->policies ?? collect())
            ),
            'childs' => $this->whenLoaded('childs', fn () => ChildResource::collection($this->childs ?? collect())
            ),
        ];
    }
}
