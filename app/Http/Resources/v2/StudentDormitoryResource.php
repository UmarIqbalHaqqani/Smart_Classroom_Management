<?php

namespace App\Http\Resources\v2;

use App\SmParent;
use App\SmStudent;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StudentDormitoryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
      if (auth()->user()->role_id == 3) {
            $parent = SmParent::where('user_id', auth()->user()->id)->first();
            $student_detail = SmStudent::where('parent_id', $parent->id)->first();  

        } else {
            $parent = SmParent::where('id', auth()->user()->id)->first();
            $student_detail = SmStudent::where('parent_id', $parent->id)->first();
        }
        
        if (@$student_detail->room_id == @$this->id) {
            $status = __('dormitory.assigned');
        } else {
            $status = __('dormitory.not_assigned');
        }
        return [
            'id' => (int)$this->id,
            'dormitory_name' => (string)$this->dormitory->dormitory_name,
            'room_number' => (string)$this->name,
            'room_type' => (string)$this->roomType->type,
            'number_of_bed' => (int)$this->number_of_bed,
            'cost_per_bed' => (float)$this->cost_per_bed,
            'status' => (string)$status,
        ];
    }
}
