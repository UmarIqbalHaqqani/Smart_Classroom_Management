<?php

namespace App\Http\Resources\v2\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AttendanceStudentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'student_id'        => (int)@$this->studentDetail->id,
            'record_id'         => (int)@$this->id,
            // 'admission_no'      => @$this->studentDetail->admission_no,
            'full_name'         => (string)@$this->studentDetail->first_name . ' ' . @$this->studentDetail->last_name,
            // 'roll_no'           => @$this->studentDetail->roll_no,
            'note'              => (string)@$this->studentDetail->DateWiseAttendances->notes,
            'attendance_type'   => (string)@$this->studentDetail->DateWiseAttendances->attendance_type ?? 'P',
            'student_photo'     => $this->studentDetail->student_photo ? asset($this->studentDetail->student_photo) : (string)null,
            'class'             => (string)@$this->class->class_name,
            'section'           => (string)@$this->section->section_name
        ];
    }
}
