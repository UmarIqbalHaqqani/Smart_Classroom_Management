<?php

namespace App\Http\Resources\v2;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LessonTopicResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request)/* : array */
    {
        // $data = $this->resource[0];
        // return [
        //     // 'id' => $data->id,
        //     'topic_title' => @$data->topicName,
        // ];

        // return [
        //     'id' => $this->id,
        //     'topic_title' => @$this->topicName->topic_title,
        // ];

        return @$this->topicName->topic_title;

    }
}
