<?php

namespace App\Http\Controllers\api\v2\Teacher\ClassRoutine;

use App\SmStaff;
use Illuminate\Http\Request;
use App\SmClassRoutineUpdate;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Scopes\ActiveStatusSchoolScope;
use App\Scopes\StatusAcademicSchoolScope;

class ClassRoutineController extends Controller
{
    public function classRoutineSearch(Request $request)
    {
        $this->validate($request, [
            'class_id' => 'required',
            'section_id' => 'required',
        ]);
        $data = [];
        $sm_routine_updates = SmClassRoutineUpdate::withoutGlobalScope(StatusAcademicSchoolScope::class)
            ->where('school_id', auth()->user()->school_id)
            ->where('class_id', $request->class_id)
            ->where('section_id', $request->section_id)
            ->get()->map(function ($value) {
                return [
                    'id'            => (int)$value->id,
                    'day'           => $value->weekendApi ? (string)$value->weekendApi->name : '',
                    'room'          => $value->classRoomApi ? (string)$value->classRoomApi->room_no : '',
                    'subject'       => $value->subjectApi ? (string)$value->subjectApi->subject_name : '',
                    'teacher'       => $value->teacherDetailApi ? (string)$value->teacherDetailApi->full_name : '',
                    'class'         => $value->classApi ? (string)$value->classApi->class_name : '',
                    'section'       => $value->sectionApi ? (string)$value->sectionApi->section_name : '',
                    'start_time'    => (string)date('h:i A', strtotime($value->start_time)),
                    'end_time'      => (string)date('h:i A', strtotime($value->end_time)),
                    'break'         => $value->is_break ? (string)'Yes' : (string)'No',
                ];
            });
        $data['class_routines'] = $sm_routine_updates;
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

    public function teacherClassRoutine(Request $request)
    {
        $staff_detail = SmStaff::withoutGlobalScope(ActiveStatusSchoolScope::class)
            ->select('id', 'full_name', 'role_id')
            ->where('user_id', $request->user_id)
            ->where('school_id', auth()->user()->school_id)
            ->first();

        if ($staff_detail->role_id != 4) {
            return response()->json(['message' => 'You Are not teacher']);
        }
        $teacher_id = $staff_detail->id;

        $school_id = auth()->user()->school_id;

        $data['class_routines'] = SmClassRoutineUpdate::withoutGlobalScope(StatusAcademicSchoolScope::class)
            ->with(['weekend', 'classRoom', 'subject' => function ($q) use ($school_id) {
                $q->withoutGlobalScope(StatusAcademicSchoolScope::class)->where('school_id', $school_id);
            }, 'teacherDetail', 'class' => function ($q) use ($school_id) {
                $q->withoutGlobalScope(StatusAcademicSchoolScope::class)->where('school_id', $school_id);
            }, 'section' => function ($q) use ($school_id) {
                $q->withoutGlobalScope(StatusAcademicSchoolScope::class)->where('school_id', $school_id);
            }])
            ->where('teacher_id', $teacher_id)
            ->where('school_id', $school_id)->get()->map(function ($value) {
                return [
                    'id'            => (int)$value->id,
                    'day'           => $value->weekend ? (string)$value->weekend->name : '',
                    'room'          => $value->classRoom ? (string)$value->classRoom->room_no : '',
                    'subject'       => $value->subject ? (string)$value->subject->subject_name : '',
                    'teacher'       => $value->teacherDetail ? (string)$value->teacherDetail->full_name : '',
                    'class'         => $value->class ? (string)$value->class->class_name : '',
                    'section'       => $value->section ? (string)$value->section->section_name : '',
                    'start_time'    => (string)date('h:i A', strtotime($value->start_time)),
                    'end_time'      => (string)date('h:i A', strtotime($value->end_time)),
                    'break'         => $value->is_break ? (string)'Yes' : (string)'No',

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
                'message' => 'Your class routine list'
            ];
        }
        return response()->json($response);
    }
}
