<?php

namespace App\Http\Resources\v2\Teacher;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class HomeworkEvaluationListResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // return parent::toArray($request);

        return [
            'id' => $this->id,
            'subject_name' => @$this->subjects->subject_name,
            'evaluate_date' => $this->evaluation_date,
            'file' => $this->file ? asset($this->file) : null,
            'marks' => $this->marks,
            'submission_date' => $this->submission_date
        ];
    }
}
