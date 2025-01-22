<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SmStudentRegistrationField extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $casts = [
        'field_name'    => 'string',
        'is_show'       => 'integer',
        'student_edit'  => 'integer',
    ];
}
