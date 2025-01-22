<?php

namespace App\Http\Resources\v2;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ExamResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'        => (int)$this->id,
            'exam_type' => (string)@$this->examType->title,
            'class'     => (string)@$this->class->class_name,
            'section'   => (string)@$this->section->section_name,
            'subject'   => (string)@$this->subject->subject_name,
        ];
    }
}
