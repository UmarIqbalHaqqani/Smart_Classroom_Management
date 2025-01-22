<?php

namespace App;

use App\Scopes\AcademicSchoolScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SmStudentAttendance extends Model
{
    use HasFactory;
    protected $table = "sm_student_attendances";
    protected $guarded = [];
    protected $casts = [
        'attendance_type' => 'string',
        'attendance_date' => 'string',
    ];
    
    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope(new AcademicSchoolScope);
    }
    public function studentInfo()
    {
        return $this->belongsTo('App\SmStudent', 'student_id', 'id');
    }
    public function scopemonthAttendances($query, $month)
    {
        return $query->whereMonth('attendance_date', $month);
    }
}
