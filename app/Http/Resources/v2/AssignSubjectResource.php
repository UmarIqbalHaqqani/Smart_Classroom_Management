<?php

namespace App\Http\Resources\v2;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AssignSubjectResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'        => (int)$this->subject->id,
            'subject'   => (string)@$this->subject->subject_name . ' - ( ' . @$this->subject->subject_code . ' )',
            'teacher'   => (string)@$this->teacher->full_name,
            'type'      => $this->subject->subject_type == "T" ? (string)'Theory' : (string)'Practical',
        ];
    }
}
