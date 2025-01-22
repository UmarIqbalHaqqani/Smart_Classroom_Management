<?php

namespace App\Http\Resources\v2\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LibraryMemberListResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'student_id'=>$this->student_staff_id,
            'parent_id'=>$this->staffDetails->parent_id,
            'staff_id'=>$this->student_staff_id,
            'user_id'=>$this->staffDetails->user_id,
            'member_type'=>$this->staffDetails->role_id,
            'member_ud_id'=>$this->member_ud_id
        ];
    }
}
