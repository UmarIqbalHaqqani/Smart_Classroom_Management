<?php

namespace App\Http\Resources\v2\Class\Student\BBB;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MeetingResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // return parent::toArray($request);

        if ($this->current_status == 'started') {
            $joinUrl = route('bbb.meeting.join', $this->meeting_id);
            if (auth()->user()->role_id == 1 || auth()->user()->role_id == 4 || auth()->user()->id == $this->created_by) {
                $currentStatus = strtoupper('start');
            } else {
                $currentStatus = strtoupper('join');
            }
        } elseif ($this->current_status == 'waiting') {
            $currentStatus = strtoupper('waiting');
        } else {
            $currentStatus = strtoupper('closed');
        }

        return [
            'meeting_id' => $this->meeting_id,
            'meeting_password' => $this->attendee_password,
            'topic' => $this->topic,
            'duration' => (string)$this->duration,
            'start_date' => date('M d', strtotime($this->start_time)),
            'start_time' => date('h:i A', strtotime($this->start_time)),
            'current_status' => $currentStatus,
            'join_url' => @$joinUrl
        ];
    }
}
