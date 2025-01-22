<?php

namespace App\Http\Resources\v2\Teacher\Subject;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SubjectListResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'            => (int)$this->id,
            'subject_name'  => (string)$this->subject_name,
            'subject_code'  => (string)$this->subject_code,
            'subject_type'  => $this->subject_type == 'T' ? (string)'Theory' : (string)'Practical',
        ];
    }
}
