<?php

namespace App\Http\Resources\v2;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LessonSubTopicResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request)/* : array */
    {
        // return parent::toArray($request);
        // return [
        //     // 'id' => $this->id,
        //     'sub_topic_title' => $this->sub_topic_title,
        // ];

        return $this->sub_topic_title;
    }
}
