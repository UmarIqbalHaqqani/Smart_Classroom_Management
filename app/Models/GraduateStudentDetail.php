<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class GraduateStudentDetail extends Model
{
    use HasFactory;

    protected $fillable = [];
    
    protected static function newFactory()
    {
        return \Modules\Alumni\Database\factories\GraduateStudentDetailFactory::new();
    }

    public function graduate()
    {
        return $this->belongsTo(Graduate::class);
    }

    public function student()
    {
        return $this->belongsTo('App\Models\SmStudent', 'student_id', 'id');
    }
}
