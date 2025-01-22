<?php

namespace App\Http\Controllers\api\v2\Teacher;

use App\Http\Controllers\Controller;
use App\Http\Resources\v2\TeachersListResource;
use App\Models\StudentRecord;
use App\Models\TeacherEvaluationSetting;
use App\Scopes\StatusAcademicSchoolScope;
use App\SmAssignSubject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TeacherController extends Controller
{
    public function studentTeacher(Request $request)
    {
        $record = StudentRecord::where('school_id', auth()->user()->school_id)->where('id', $request->record_id)->firstOrFail();
        $result = SmAssignSubject::withoutGlobalScope(StatusAcademicSchoolScope::class)
            ->with('teacher', 'subject')
            ->where('school_id', auth()->user()->school_id)
            ->where('class_id', $record->class_id)
            ->where('section_id', $record->section_id)
            ->distinct('teacher_id')->get();

        $data = TeachersListResource::collection($result);
        
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
                'message' => "Student teacher list"
            ];
        }
        return response()->json($response);
    }
}
