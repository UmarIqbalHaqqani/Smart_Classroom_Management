<?php

namespace App\Http\Resources\v2;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StudentBookListResource extends JsonResource
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
            'book_title' => (string)$this->book_title,
            'book_number' => (string)$this->book_number,
            'isbn_no' => (string)$this->isbn_no,
            'category' => (string)$this->bookCategory->category_name,
            'subject' => (string)$this->bookSubject->subject_name,
            'publisher_name' => (string)$this->publisher_name,
            'author_name' => (string)$this->author_name,
            'quantity' => (int)$this->quantity,
            'price' => (float)$this->book_price,
            'rack_number' => (string)$this->rack_number,
        ];
    }
}
