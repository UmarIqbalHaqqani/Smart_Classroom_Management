<?php

namespace App\Http\Resources\v2;

use DateTime;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StudentIssuedBookListResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $now = new DateTime($this->given_date);
        $end = new DateTime($this->due_date);
        if ($this->issue_status == 'I') {
            if ($end < $now) {
                $status = __('library.expired');
            } else {
                $status = __('library.issued');
            }
        }
        return [
            'id' => (int)$this->id,
            'book_title' => (string)$this->book_title,
            'book_number' => (string)$this->book_number,
            'author_name' => (string)$this->author_name,
            'subject' => (string)$this->subject_name,
            'issue_date' => (string)$this->given_date,
            'return_date' => (string)$this->due_date,
            'status' => (string)$status,
        ];
    }
}
