<?php

namespace App\Http\Resources;

use App\Models\StudentRecord;
use App\SmClass;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SmStudentResourse extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'                => (int)$this->id,
            'student_photo'     => $this->student_photo ? (string)asset($this->student_photo) : (string)null,
            'first_name'        => (string)$this->first_name,
            'last_name'         => (string)$this->last_name,
            'admission_no'      => (int)$this->admission_no,
            'date_of_birth'     => (string)$this->date_of_birth,
            'age'               => (int)$this->age,
            'mobile'            => (string)$this->mobile,
            'email'             => (string)$this->email,
            'current_address'   => (string)$this->current_address,
            'permanent_address' => (string)$this->permanent_address,
            'blood_group'       => (string)@$this->bloodGroup->base_setup_name,
            'religion'          => (string)@$this->religion->base_setup_name,
            'roll'              => (int)@$this->roll_no,
            'class'             => (string)@$this->defaultClass->class->class_name,
            'section'           => (string)@$this->defaultClass->section->section_name
        ];
    }
}
