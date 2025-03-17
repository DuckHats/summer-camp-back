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
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'status' => $this->status,
            'email' => $this->email,
            'email_verified_at' => $this->email_verified_at,
            'phone' => $this->phone,
            'phone_verified' => $this->phone_verified,
            'profile_picture_url' => $this->profile_picture_url,
            'profile_short_description' => $this->profile_short_description,
            'profile_description' => $this->profile_description,
            'gender' => $this->gender,
            'location' => $this->location,
            'birth_date' => $this->birth_date,
            'cv_path' => $this->cv_path,
            'portfolio_url' => $this->portfolio_url,
            'level' => $this->level,
            'remember_token' => $this->remember_token,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'user_settings' => UserSettingResource::collection($this->whenLoaded('settings')),
            'user_policies' => PolicyResource::collection($this->whenLoaded('policies')),
        ];
    }
}
