<?php

namespace App\Http\Controllers\api\v2\ClassRoutine;

use App\SmStudent;
use Illuminate\Http\Request;
use App\Models\StudentRecord;
use App\SmClassRoutineUpdate;
use App\Http\Controllers\Controller;
use App\Scopes\StatusAcademicSchoolScope;

class ClassRoutineController extends Controller
{
    public function studentClassRoutine(Request $request)
    {
        $school_id = auth()->user()->school_id;

        $record = StudentRecord::where('school_id', $school_id)->select('class_id', 'section_id', 'id')->findOrFail($request->record_id);

        $class_id = $record->class_id;
        $section_id = $record->section_id;

        $data['class_routines'] = SmClassRoutineUpdate::withOutGlobalScope(StatusAcademicSchoolScope::class)
            ->with('weekend', 'classRoom', 'subject', 'teacherDetail', 'class', 'section')
            ->where('class_id', $class_id)
            ->where('section_id', $section_id)
            ->where('school_id', $school_id)
            ->get()->map(function ($value) {
                return [
                    'id' => (int)$value->id,
                    'day' => $value->weekendApi ? (string)$value->weekendApi->name : '',
                    'room' => $value->classRoomApi ? (string)$value->classRoomApi->room_no : '',
                    'subject' => $value->subjectApi ? (string)$value->subjectApi->subject_name : '',
                    'teacher' => $value->teacherDetailApi ? (string)$value->teacherDetailApi->full_name : '',
                    'class' => $value->classApi ? (string)$value->classApi->class_name : '',
                    'section' => $value->sectionApi ? (string)$value->sectionApi->section_name : '',
                    'start_time' => (string)date('h:i A', strtotime($value->start_time)),
                    'end_time' => (string)date('h:i A', strtotime($value->end_time)),
                    'break' => $value->is_break ? (string)'Yes' : (string)'No',
                ];
            });
        if (!$data) {
            $response = [
                'success' => false,
                'data'    => null,
                'message' => 'Operation failed'
            ];
        } else {
            $response = [
                'success' => true,
                'data'    => $data,
                'message' => 'Class routine list'
            ];
        }
        return response()->json($response);
    }
}
