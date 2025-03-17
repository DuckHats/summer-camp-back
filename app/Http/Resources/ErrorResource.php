<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ErrorResource extends JsonResource
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
            'id_error' => $this->id_error,
            'error_code' => $this->error_code,
            'error_message' => $this->error_message,
            'stack_trace' => $this->stack_trace,
            'user_id' => $this->user_id,
            'session_id' => $this->session_id,
            'occurred_at' => $this->occurred_at,
        ];
    }
}
