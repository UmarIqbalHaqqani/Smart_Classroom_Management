<?php

namespace App\Http\Resources\v2\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StudentListResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'            => (int)$this->id,
            'full_name'     => (string)$this->first_name . ' ' . $this->last_name,
            'student_photo' => $this->student_photo ? (string)asset($this->student_photo) : (string)null,
            'class_section' => StudentClassSectionResource::collection(@$this->studentRecords),
        ];
    }
}
