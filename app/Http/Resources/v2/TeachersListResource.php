<?php

namespace App\Http\Resources\v2;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TeachersListResource extends JsonResource
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
            'staff_photo'   => @$this->teacher->staff_photo ? (string)asset($this->teacher->staff_photo) : (string)null,
            'full_name'     => (string)$this->teacher->full_name,
            'subject_name'  => (string)$this->subject->subject_name,
            'email'         => (string)$this->teacher->email,
            'mobile'        => (string)$this->teacher->mobile
        ];
    }
}
