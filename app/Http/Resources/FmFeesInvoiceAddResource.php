<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FmFeesInvoiceAddResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'record_id'         => (int)$this->recordDetail->id,
            'student_id'        => (int)$this->studentInfo->id,
            'student_photo'     => @$this->studentInfo->student_photo ? (string)asset($this->studentInfo->student_photo) : (string)null,
            'full_name'         => (string)$this->studentInfo->full_name,
            'admission_no'      => (int)$this->studentInfo->admission_no,
            'roll_no'           => (int)$this->studentInfo->roll_no,
            'class'             => (string)@$this->recordDetail->class->class_name,
            'section'           => (string)@$this->recordDetail->section->section_name,
            'wallet_balance'    => (float)@$this->studentInfo->user->wallet_balance
        ];
    }
}
