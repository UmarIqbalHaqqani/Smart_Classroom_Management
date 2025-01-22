<?php

namespace App\Http\Resources\v2\Class\Student\Zoom;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ClassResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // return parent::toArray($request);

        return [
            'class_id' => $this->id,
            'class_name' => $this->class_name,
        ];
    }
}
