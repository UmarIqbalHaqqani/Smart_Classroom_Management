<?php

namespace App\Http\Resources\v2\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class IndividualStaffDetailsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'                => (int)$this->id,
            'first_name'        => (string)$this->first_name,
            'last_name'         => (string)$this->last_name,
            'mobile'            => (string)$this->mobile,
            'current_address'   => (string)$this->current_address,
            'permanent_address' => (string)$this->permanent_address,
            'staff_photo'       => $this->staff_photo ? (string)asset($this->staff_photo) : (string)null,
            'qualification'     => (string)$this->qualification,
            'marital_status'    => (string)$this->marital_status,
            'date_of_joining'   => (string)$this->date_of_joining,
            'designation'       => (string)@$this->designations->title
        ];
    }
}