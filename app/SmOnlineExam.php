<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Scopes\StatusAcademicSchoolScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\OnlineExam\Entities\InfixStudentTakeOnlineExam;

class SmOnlineExam extends Model
{
    use HasFactory;
    protected static function boot(){
        parent::boot();
        static::addGlobalScope(new StatusAcademicSchoolScope);
    }


    public function studentInfo()
    {
        return $this->belongsTo('App\SmStudent', 'id', 'student_id');
    }

    public function class()
    {
        return $this->belongsTo('App\SmClass', 'class_id', 'id');
    }

    public function section()
    {
        if (moduleStatusCheck('University')) {
            return $this->belongsTo('App\SmSection', 'un_section_id', 'id');
        } else {
            return $this->belongsTo('App\SmSection', 'section_id', 'id');
        }


    }

    public function subject()
    {
        if (moduleStatusCheck('University')) {
            return $this->belongsTo('Modules\University\Entities\UnSubject', 'un_subject_id', 'id');
        } else {
            return $this->belongsTo('App\SmSubject', 'subject_id', 'id');
        }
    }

    public function unSemesterLabel()
    {
        return $this->belongsTo('Modules\University\Entities\UnSemesterLabel', 'un_semester_label_id', 'id')->withDefault();
    }

    public function assignQuestions()
    {
        return $this->hasMany('App\SmOnlineExamQuestionAssign', 'online_exam_id', 'id')->withOutGlobalScopes();
    }

    public static function obtainedMarks($exam_id, $student_id, $record_id = null)
    {

        try {
            if (moduleStatusCheck('OnlineExam')==true) {
                $marks = InfixStudentTakeOnlineExam::select('status', 'student_done', 'total_marks')
                    ->where('online_exam_id', $exam_id)->where('student_id', $student_id)
                    ->where('student_record_id', $record_id)
                    ->first();
            } else {
                $marks = SmStudentTakeOnlineExam::select('status', 'total_marks')
                    ->where('online_exam_id', $exam_id)
                    ->where('student_id', $student_id)
                    ->first();
            }
            return $marks;
        } catch (\Exception $e) {
            $data = [];
            return $data;
        }
    }

    public function studentAttend()
    {
        return $this->hasOne('App\SmStudentTakeOnlineExam', 'online_exam_id', 'id');
    }

    public function smStudentTakeOnlineExam()
    {
        return $this->hasMany('App\SmStudentTakeOnlineExam', 'online_exam_id', 'id');
    }

}
