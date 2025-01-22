<?php

namespace App\Http\Resources\v2\Chat\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class GroupchatMessageResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $thread = $this['thread'];
        $message = $this['message'];

        if ($thread->user_id == auth()->user()->id) {
            $sender = true;
            $receiver = false;
        } else {
            $sender = false;
            $receiver = true;
        }

        return [
            'thread_id'             => (int)$thread->id,
            'message_id'            => (int)$message->id,
            'message'               => (string)$message->message,
            'status'                => $message->status,
            'message_type'          => (int)$message->message_type,
            'file'                  => $message->file_name ? (string)asset($message->file_name) : null,
            'original_file_name'    => (string)$message->original_file_name,
            'forwarded'             => (bool)$message->forward,
            'reply'                 => (bool)@$message->replyId->message,
            'reply_for'             => (string)@$message->replyId->message,
            'sender'                => (bool)$sender,
            'receiver'              => (bool)$receiver
        ];
    }
}
