<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SmStudentTimeline extends Model
{
    use HasFactory;

    protected $casts = [
        'id'            => 'integer',
        'date'          => 'string',
        'title'         => 'string',
        'description'   => 'string',
        'file'          => 'string',
        'created_at'    => 'string',
    ];
}
