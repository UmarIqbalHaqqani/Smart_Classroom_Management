<?php

namespace App\Http\Resources\v2\Teacher;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class HomeworkListResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'                => (int)$this->id,
            'subject_name'      => (string)@$this->subjects->subject_name,
            'assign_date'       => (string)date('Y-m-d', strtotime($this->homework_date)),
            'submission_date'   => (string)date('Y-m-d', strtotime($this->submission_date)),
            'evaluation'        => (int)$this->evaluated_by,
            'marks'             => (float)$this->marks,
            'file'              => $this->file ? (string)asset($this->file) : null,
            'class_id'          => (int)@$this->class_id,
            'section_id'        => (int)@$this->section_id,
            'class_name'        => (string)@$this->classes->class_name,
            'section_name'      => (string)@$this->sections->section_name
        ];
    }
}
