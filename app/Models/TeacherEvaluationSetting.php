<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TeacherEvaluationSetting extends Model
{
    use HasFactory;
    protected $casts= [ 'submitted_by' => 'array'];
}
