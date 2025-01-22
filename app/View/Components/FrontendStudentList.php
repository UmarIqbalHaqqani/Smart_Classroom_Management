<?php

namespace App\View\Components;

use Closure;
use App\SmClass;
use App\SmSection;
use App\SmStudent;
use App\SmAcademicYear;
use App\Models\StudentRecord;
use Illuminate\View\Component;
use Illuminate\Contracts\View\View;

class FrontendStudentList extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        $academicYears = SmAcademicYear::get();
        $student_ids  = StudentRecord::when(request('academic_year'), function($q){
            $q->where('academic_id', request('academic_year'));
        })->when(request('section'), function($q){
            $q->where('section_id', request('section'));
        })->when(request('class'), function($q){
            $q->where('class_id', request('class'));
        })
        ->where('school_id', app('school')->id)->pluck('student_id');
        $students = SmStudent::whereIn('id',$student_ids)->with('parents', 'bloodGroup', 'studentRecord.class', 'studentRecord.section')->get();
        $req_data = [] ;
        $req_data['class'] = SmClass::find(request('class'));
        $req_data['section'] = SmSection::find(request('section'));
        
        return view('components.' . activeTheme() . '.frontend-student-list', compact('students', 'academicYears','req_data'));
    }
}
