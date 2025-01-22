<?php

namespace App\Http\Resources\v2\Chat\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SingleChatResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        if ($this->from_id == auth()->user()->id) {
            $sender = true;
            $receiver = false;
        } else {
            $sender = false;
            $receiver = true;
        }

        return [
            'message_id'    => (int)$this->id,
            'message'       => (string)$this->message,
            'file'          => $this->file_name ? (string)asset($this->file_name) : null,
            'file_name'     => (string)$this->original_file_name,
            'message_type'  => (int)$this->message_type,
            'forwarded'     => (bool)$this->forward,
            'reply'         => (bool)@$this->replyId->message,
            'reply_for'     => (string)@$this->replyId->message,
            'sender'        => (bool)$sender,
            'receiver'      => (bool)$receiver
        ];
    }
}
