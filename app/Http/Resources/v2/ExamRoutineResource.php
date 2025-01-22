<?php

namespace App\Http\Resources\v2;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ExamRoutineResource extends JsonResource
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
            'date_and_day' => (string)dateConvert($this->date).' '.Carbon::createFromFormat('Y-m-d', $this->date)->format('l'),
            'subject' => (string)@$this->subject->subject_name,
            'subject_class_section' => (string)@$this->subject->subject_name. ' - ' .@$this->class->class_name.' ('.@$this->section->section_name.') ',
            'teacher' => (string)@$this->teacher->full_name,
            'start_time' => (string)date('h:i A', strtotime(@$this->start_time)),
            'end_time' => (string)date('h:i A', strtotime(@$this->end_time)),
            'duration' => (string)timeCalculation(strtotime($this->end_time)-strtotime($this->start_time)),
            'room' => (string)@$this->classRoom->room_no,
        ];
    }
}
