<?php

namespace App\Http\Resources\v2\Teacher\Leave;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AppliedLeveListResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        if ($this->approve_status == 'P') {
            $status = __('common.pending');
        } elseif ($this->approve_status == 'A') {
            $status = __('common.approved');
        } elseif ($this->approve_status == 'C') {
            $status = __('leave.cancelled');
        }
        return [
            'id'            => (int)$this->id,
            'leave_type'    => (string)@$this->leaveDefine->leaveType->type,
            'from'          => (string)dateConvert($this->leave_from),
            'to'            => (string)dateConvert($this->leave_to),
            'apply_date'    => (string)dateConvert($this->apply_date),
            'status'        => (string)$status,
            'reason'        => (string)$this->reason
        ];
    }
}
