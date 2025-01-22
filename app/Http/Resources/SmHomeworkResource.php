<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SmHomeworkResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // return parent::toArray($request);

        if (isset($this->lmsHomeworkCompleted->complete_status) && ($this->lmsHomeworkCompleted->complete_status == 'C')) {
            $status = 'Completed';
        } else {
            $status = 'Incompleted';
        }

        return [
            'id'                => (int)$this->id,
            'created_at'        => (string)date('Y-m-d', strtotime($this->created_at)),
            'submission_date'   => (string)$this->submission_date,
            'evaluation_date'   => (string)$this->evaluation_date,
            'status'            => (string)@$status,
            'marks'             => (float)$this->marks,
            'subject'           => (string)@$this->subjects->subject_name,
            'file'              => $this->file ? (string)asset($this->file) : (string)null
        ];
    }
}
