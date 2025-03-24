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
            'user_settings' => UserSettingResource::collection($this->whenLoaded('settings')),
            'user_policies' => PolicyResource::collection($this->whenLoaded('policies')),
            'childs' => ChildResource::collection($this->whenLoaded('childs')),
        ];
    }
}
