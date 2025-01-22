<?php

namespace App\Http\Resources\v2\Chat\Admin;

use Illuminate\Http\Request;
use Modules\Chat\Entities\BlockUser;
use Modules\Chat\Entities\Conversation;
use Illuminate\Http\Resources\Json\JsonResource;

class ChatUserListResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $conversation = $this->userSpecificConversation($this->id);
        $block = BlockUser::where('block_by', auth()->id())->where('block_to', $this->id)->first();

        switch (@$this->activeStatus->status) {
            case 0:
                $status = 'INACTIVE';
                $color = '0xFFE1E2EC';
                break;
            case 1:
                $status = 'ACTIVE';
                $color = '0xFF12AE01';
                break;
            case 2:
                $status = 'AWAY';
                $color = '0xFFF99F15';
                break;
            case 3:
                $status = 'BUSY';
                $color = '0xFFF60003';
                break;
        }

        return [
            'user_id'           => (int)$this->id,
            'full_name'         => (string)$this->full_name,
            'image'             => $this->avatar_url ? (string)asset($this->avatar_url) : null,
            'blocked'           => (bool)($this->id == @$block->block_to),
            'active_status'     => (string)$status,
            'status_color'      => (string)$color,
            'last_message'      => (string)@$conversation->first()->message,
            'last_message_time' => (string)date('Y-m-d H:i:s', strtotime(@$conversation->first()->created_at)),
            // 'count_conversation' => count($this->oppositeConversations)
        ];
    }
}
