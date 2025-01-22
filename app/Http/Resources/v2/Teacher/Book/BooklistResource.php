<?php

namespace App\Http\Resources\v2\Teacher\Book;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BooklistResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'                => (int)$this->id,
            'book_title'        => (string)$this->book_title,
            'subject_name'      => (string)@$this->bookSubject->subject_name,
            'publisher_name'    => (string)$this->publisher_name,
            'author_name'       => (string)$this->author_name,
            'book_number'       => (string)$this->book_number,
            'quantity'          => (int)$this->quantity,
            'book_price'        => (float)$this->book_price,
            'rack_number'       => (string)$this->rack_number
        ];
    }
}
