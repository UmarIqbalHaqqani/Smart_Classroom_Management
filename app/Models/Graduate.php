<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Graduate extends Model
{
    use HasFactory;

    protected $fillable = [];
    
    protected static function newFactory()
    {
        return \Modules\Alumni\Database\factories\GraduateFactory::new();
    }
    public function student()
    {
        return $this->belongsTo('App\SmStudent', 'student_id', 'id')->withDefault();
    }

    public function unSession()
    {
        return $this->belongsTo('Modules\University\Entities\UnSession', 'un_session_id', 'id')->withDefault();
    }
    public function unFaculty()
    {
        return $this->belongsTo('Modules\University\Entities\UnFaculty', 'un_faculty_id', 'id')->withDefault();
    }
    public function unDepartment()
    {
        return $this->belongsTo('Modules\University\Entities\UnDepartment', 'un_department_id', 'id')->withDefault();
    }

    public function unAlumni()
    {
        return $this->hasOne('Modules\Alumni\Entities\Alumni','un_graduate_id');
    }

    public function graduateStudentDetail()
    {
        return $this->hasOne('Modules\Alumni\Entities\GraduateStudentDetail','graduate_id');
    }
    #sm_record_table
    public function section()
    {
        return $this->belongsTo('App\SmSection', 'section_id', 'id')->withDefault();
    }

    public function smClass()
    {
        return $this->belongsTo('App\SmClass', 'class_id', 'id')->withDefault();
    }
}
