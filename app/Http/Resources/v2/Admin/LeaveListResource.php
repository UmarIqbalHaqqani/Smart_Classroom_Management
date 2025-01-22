<?php

namespace App\Http\Resources\v2\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LeaveListResource extends JsonResource
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
            'full_name' => (string)@$this->user->full_name,
            'apply_date' => (string)$this->apply_date,
            'leave_from' => (string)$this->leave_from,
            'leave_to' => (string)$this->leave_to,
            'reason' => (string)$this->reason,
            'file' => $this->file ? (string)asset($this->file) : (string)null,
            'type' => (string)@$this->leaveType->type,
            'approve_status' => (string)$this->approve_status
        ];
    }
}
