<?php

namespace App\Http\Resources\v2\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AttendanceStudentListResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => (int)$this->studentDetail->id,
            'full_name' => (string)@$this->studentDetail->first_name . ' ' . @$this->studentDetail->last_name,
            'class' => (string)@$this->class->class_name,
            'section' => (string)@$this->section->section_name,
            'student_photo' => $this->studentDetail->student_photo ? (string)asset($this->studentDetail->student_photo) : (string)null

        ];
    }
}
