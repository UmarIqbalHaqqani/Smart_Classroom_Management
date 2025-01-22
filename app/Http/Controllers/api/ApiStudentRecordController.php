<?php

namespace App\Http\Controllers\api;

use App\SmStudent;
use App\SmAcademicYear;
use App\Scopes\SchoolScope;
use Illuminate\Http\Request;
use App\Models\StudentRecord;
use App\Http\Controllers\Controller;

class ApiStudentRecordController extends Controller
{
    //
    public function getRecord($student_id)
    {
    
        $records = StudentRecord::where('student_id',$student_id)->where('academic_id', SmAcademicYear::SINGLE_SCHOOL_API_ACADEMIC_YEAR())->get()->map(function ($record) {
            return[
                'id'=>$record->id,
                'student_id'=>$record->student_id,
                'full_name'=>$record->student->full_name,
                'class'=>$record->class->class_name,
                'section'=>$record->section->section_name,
                'class_id'=>$record->class_id,
                'section_id'=>$record->section_id,
                'is_default'=>$record->is_default,
                'is_promote'=>$record->is_promote,
                'roll_no'=>$record->roll_no,
                'session_id'=>$record->session_id,
                'academic_id'=>$record->academic_id,
                'school_id'=>$record->school_id,
            ];
        });
        return response()->json(compact('records'));
    }

    public function getRecordSaas($school_id, $record_id)
    {
        $record = StudentRecord::find($record_id);
        $student_id = SmStudent::withOutGlobalScope(SchoolScope::class)->where('id',$record->student_id)->value('id');
        $records = StudentRecord::where('school_id',$school_id)->where('student_id',$student_id)->where('academic_id', SmAcademicYear::API_ACADEMIC_YEAR($school_id))->get()->map(function ($record) {
            return[
                'id'=>$record->id,
                'student_id'=>$record->student_id,
                'full_name'=>$record->saasstudent->full_name,
                'class'=>$record->class->class_name,
                'section'=>$record->section->section_name,
                'is_default'=>$record->is_default,
                'is_promote'=>$record->is_promote,
                'roll_no'=>$record->roll_no,
                'session_id'=>$record->session_id,
                'academic_id'=>$record->academic_id,
                'school_id'=>$record->school_id,
            ];
        });
        return response()->json(compact('records'));
    }
}
