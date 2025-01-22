<?php

namespace App\Http\Resources\v2\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DormitoryRoomListResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => (int)$this->id,
            'name' => (string)$this->name,
            'number_of_bed' => (int)$this->number_of_bed,
            'cost_per_bed' => (float)$this->cost_per_bed,
            // 'description' => $this->description,
            'dormitory' => (string)@$this->dormitory->dormitory_name,
            'room_type' => (string)@$this->roomType->type
        ];
    }
}
