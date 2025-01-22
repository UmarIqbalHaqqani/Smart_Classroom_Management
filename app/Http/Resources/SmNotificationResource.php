<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SmNotificationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'message'       => (string)$this->message,
            'created_at'    => (string)$this->created_at,
            'user_photo'    => @$this->user->avatar_url ? (string)asset($this->user->avatar_url) : (string)null
        ];
    }
}
