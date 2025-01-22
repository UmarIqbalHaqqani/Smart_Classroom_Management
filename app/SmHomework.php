<?php

namespace App;

use App\SmClass;
use App\Models\StudentRecord;
use App\Scopes\GlobalAcademicScope;
use Illuminate\Database\Eloquent\Model;
use App\Scopes\StatusAcademicSchoolScope;
use Modules\University\Entities\UnSubject;
use Modules\University\Entities\UnSemesterLabel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\University\Entities\UnSemesterLabelAssignSection;

class SmHomework extends Model
{
    use HasFactory;
    protected $table = "sm_homeworks";
    protected $fillable = [
        'class_id', 'section_id', 'subject_id', 'created_by', 'evaluated_by',
    ];
    protected $appends=['HomeworkPercentage'];

    protected $casts = [
        'id'                => 'integer',
        'homework_date'     => 'string',
        'submission_date'   => 'string',
        'evaluation_date'   => 'string',
        'file'              => 'string',
        'marks'             => 'string',
        'description'       => 'string',
        'active_status'     => 'integer',
        'created_at'        => 'string',
        'updated_at'        => 'string',
        'evaluated_by'      => 'integer',
        'class_id'          => 'string',
        'record_id'         => 'integer',
        'section_id'        => 'string',
        'subject_id'        => 'integer',
        'created_by'        => 'integer',
        'updated_by'        => 'integer',
        'school_id'         => 'integer',
        'academic_id'       => 'integer',
        'course_id'         => 'integer',
        'lesson_id'         => 'integer',
        'chapter_id'        => 'integer',
    ];

    protected static function boot(){
        parent::boot();
        static::addGlobalScope(new StatusAcademicSchoolScope);
    }
    public function classes(){
        return $this->belongsTo('App\SmClass', 'class_id', 'id');
    }


    public function class()
    {
        if (moduleStatusCheck('University')) {
            return $this->belongsTo(UnSemesterLabel::class, 'un_semester_label_id', 'id');
        } else {
            return $this->belongsTo('App\SmClass', 'class_id', 'id');
        }
    }

    public function saasclass()
    {
        return $this->belongsTo('App\SmClass', 'class_id', 'id')->withOutGlobalScope(StatusAcademicSchoolScope::class, GlobalAcademicScope::class);
    }

    public function sections()
    {
        return $this->belongsTo('App\SmSection', 'section_id', 'id');
    }
    public function section()
    {
        return $this->belongsTo('App\SmSection', 'section_id', 'id');
    }
    public function unSection(){
        return $this->belongsTo(UnSemesterLabelAssignSection::class, 'un_section_id', 'id');
    }

    public function saassection()
    {
        return $this->belongsTo('App\SmSection', 'section_id', 'id')->withOutGlobalScope(StatusAcademicSchoolScope::class,GlobalAcademicScope::class);
    }

    public function homeworkCompleted()
    {
        return $this->hasMany('App\SmHomeworkStudent', 'homework_id', 'id')->where('complete_status', 'C');
    }

    public function complete()
    { 
        return $this->hasOne('Modules\Lms\Entities\LessonComplete', 'lesson_id', 'id')->when(auth()->user()->role_id==2, function ($q) {
                $q->where('student_id', auth()->user()->student->id);
        });
    }
    

    public function lmsHomeworkCompleted()
    {
        return $this->hasOne('App\SmHomeworkStudent', 'homework_id','id');
    }


    public function subjects()
    {
        if (moduleStatusCheck('University')) {
            return $this->belongsTo(UnSubject::class, 'un_subject_id', 'id');
        } else {
            return $this->belongsTo('App\SmSubject', 'subject_id', 'id');
        }
    }

    public function saassubject()
    {
        return $this->belongsTo('App\SmSubject', 'subject_id', 'id')->withOutGlobalScope(StatusAcademicSchoolScope::class,GlobalAcademicScope::class);
    }

    public function users()
    {
        return $this->belongsTo('App\User', 'created_by', 'id');
    }

    public function saasusers()
    {
        return $this->belongsTo('App\User', 'created_by', 'id')->withOutGlobalScope(StatusAcademicSchoolScope::class);
    }

    public function evaluatedBy()
    {
        return $this->belongsTo('App\User', 'evaluated_by', 'id');
    }

    public static function getHomeworkPercentage($class_id, $section_id, $homework_id)
    {
        try {
            $totalStudents = StudentRecord::where('class_id', $class_id)
                ->where('section_id', $section_id)
                ->where('school_id', auth()->user()->school_id)
                ->where('academic_id', getAcademicId())
                ->count();
            $totalHomeworkCompleted = SmHomeworkStudent::select('id')
                ->where('homework_id', $homework_id)
                ->where('school_id', auth()->user()->school_id)
                ->where('academic_id', getAcademicId())
                ->where('complete_status', 'C')
                ->count();



            if (isset($totalStudents)) {
                $homeworks = array(
                    'totalStudents' => $totalStudents,
                    'totalHomeworkCompleted' => $totalHomeworkCompleted,

                );
                return $homeworks;
            } else {
                return false;
            }
        } catch (\Exception $e) {
            return false;
        }
    }
    public function getHomeworkPercentageAttribute()
    {
        try {
            $totalStudents = SmStudent::withOutGlobalScope(StatusAcademicSchoolScope::class)->select('id')
                ->where('class_id', $this->class_id)
                ->where('section_id', $this->section_id)
                ->where('school_id', auth()->user()->school_id)
              
                ->count();

            $totalHomeworkCompleted = SmHomeworkStudent::select('id')
                ->where('homework_id', $this->homework_id)
                ->where('academic_id', getAcademicId())
                ->where('complete_status', 'C')
                ->count();

            if (isset($totalStudents)) {
                $homeworks = array(
                    'totalStudents' => $totalStudents,
                    'totalHomeworkCompleted' => $totalHomeworkCompleted,

                );
                return $homeworks;
            } else {
                return false;
            }
        } catch (\Exception $e) {
            return false;
        }
    }

    public function evaluations()
    {
        return $this->hasMany('App\SmHomeworkStudent', 'homework_id', 'id');
    }

    public function contents()
    {
        return $this->hasMany('App\SmUploadHomeworkContent', 'homework_id', 'id');
    }

    public static function evaluationHomework($s_id, $h_id)
    {

        try {
            $abc = SmHomeworkStudent::withOutGlobalScopes()->where('homework_id', $h_id)->where('student_id', $s_id)->first();
            return $abc;
        } catch (\Exception $e) {
            $data = [];
            return $data;
        }
    }

    public static function uploadedContent($s_id, $h_id)
    {
        try {
            $abc = SmUploadHomeworkContent::where('homework_id', $h_id)->where('student_id', $s_id)->get();
            return $abc;
        } catch (\Exception $e) {
            $data = [];
            return $data;
        }
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
    public function unAcademic()
    {
        return $this->belongsTo('Modules\University\Entities\UnAcademicYear', 'un_academic_id', 'id')->withDefault();
    }
    public function unSemester()
    {
        return $this->belongsTo('Modules\University\Entities\UnSemester', 'un_semester_id', 'id')->withDefault();
    }


    public function semester()
    {
        return $this->belongsTo('Modules\University\Entities\UnSemester', 'un_semester_id', 'id')->withDefault();
    }

    public function semesterLabel()
    {
        return $this->belongsTo('Modules\University\Entities\UnSemesterLabel', 'un_semester_label_id', 'id')->withDefault();
    }

    public function unSubject()
    {
        return $this->belongsTo('Modules\University\Entities\UnSubject', 'un_subject_id', 'id')->withDefault();
    }

    // public function records()
    // {
    //     return $this->hasManyThrough(StudentRecord::class, SmClass::class, 'id', 'class_id', 'id');
    // }

    public function course()
    {
        return $this->belongsTo('Modules\Lms\Entities\Course', 'course_id', 'id')->withDefault();
    }

    public function lesson()
    {
        return $this->belongsTo('Modules\Lms\Entities\CourseLesson', 'lesson_id', 'id')->withDefault();
    }
}
