<?php

namespace App\Http\Resources\v2;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

class StudentOnlineExamResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $totalDuration = $this->end_time != 'NULL' ? Carbon::parse($this->end_time)->diffinminutes(Carbon::parse($this->start_time)) : 0;
        $startTime = strtotime($this->date . ' ' . $this->start_time);
        $endTime = strtotime($this->date . ' ' . $this->end_time);
        $now = date('h:i:s');
        $now =  strtotime("now");
        $student = User::findOrFail($this->student_id)->student;
        @$submitted_answer = $student->studentOnlineExam->where('online_exam_id', $this->id)->first();
        if (!empty($submitted_answer)) {
            if (@$submitted_answer->status == 1) {
                if ($submitted_answer->student_done == 1){
                    $status = __('exam.already_submitted');
                }elseif ($startTime <= $now && $now <= $endTime){
                    $status = __('exam.take_exam');
                }elseif ($startTime >= $now && $now <= $endTime){
                    $status = __('common.waiting');
                }elseif ($now >= $endTime){
                    $status = __('common.closed');
                }else{
                    $status = __('exam.already_submitted');
                }
            }
        } else {
            if ($startTime <= $now && $now <= $endTime){
                $status = __('common.waiting');
            }elseif ($startTime >= $now && $now <= $endTime){
                $status = __('exam.take_exam');
            }elseif ($now >= $endTime){
                $status = __('common.closed');
            }
        }
        return [
            'id' => (int)$this->id,
            'title' => (string)$this->title,
            'class_section' => (string)@$this->class->class_name . ' (' . @$this->section->section_name . ')',
            'subject' => (string)@$this->subject->subject_name,
            'start_date' => (string)dateConvert(@$this->date),
            'end_date' => (string)dateConvert(date('m/d/Y', strtotime(@$this->end_date_time))),          
            'start_time' => (string)date('h:i A', strtotime(@$this->start_time)),
            'end_time' => (string)date('h:i A', strtotime(@$this->end_time)),
            'duration' => $this->end_time != 'NULL' ? (string)gmdate($totalDuration) : 'Unlimited',
            'status' => (string)@$status,
        ];
    }
}
