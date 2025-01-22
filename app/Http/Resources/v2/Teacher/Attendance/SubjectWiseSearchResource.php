<?php

namespace App\Http\Resources\v2\Teacher\Attendance;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SubjectWiseSearchResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'student_id'        => (int)@$this->studentDetail->id,
            'record_id'         => (int)@$this->id,
            // 'admission_no' => @$this->studentDetail->admission_no,
            'full_name'         => (string)@$this->studentDetail->first_name . ' ' . @$this->studentDetail->last_name,
            // 'roll_no' => @$this->studentDetail->roll_no,
            'note'              => (string)@$this->studentDetail->DateSubjectWiseAttendances->notes,
            'attendance_type'   => @$this->studentDetail->DateSubjectWiseAttendances ? (string)@$this->studentDetail->DateSubjectWiseAttendances->attendance_type : 'P',
            'student_photo'     => @$this->studentDetail->student_photo ? asset($this->studentDetail->student_photo) : (string)null,
            'class'             => (string)@$this->class->class_name,
            'section'           => (string)@$this->section->section_name
        ];
    }
}
