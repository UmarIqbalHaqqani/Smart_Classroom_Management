<?php

namespace App\Http\Controllers\api\v2\Teacher\Attendance;

use App\Http\Controllers\Controller;
use App\SmAcademicYear;
use Illuminate\Http\Request;
use App\SmClass;
use Illuminate\Support\Facades\Auth;
use App\SmClassSection;
use App\SmSection;
use App\SmAssignSubject;




class AttendanceReportController extends Controller
{
    public function classes()
    {
        $classes = SmClass::where('active_status', 1)
            ->where(
                'academic_id',
                SmAcademicYear::SINGLE_SCHOOL_API_ACADEMIC_YEAR()
            )
            ->where('school_id', Auth::user()->school_id)
            ->get()
            ->map(function ($class) {
                return [
                    'id' => $class->id,
                    'class_name' => $class->class_name
                ];
            });

        $response = [
            'success' => true,
            'data' => $classes,
            'message' => 'Operation Successfull.'
        ];

        return response()->json($response, 200);
    }

    public function sections(Request $request)
    {
        $this->validate($request,[
            'class_id' => 'required'
        ]);

        if ($request->parent) {
            $class = SmClass::withoutGlobalScope(GlobalAcademicScope::class)
                ->withoutGlobalScope(StatusAcademicSchoolScope::class)
                ->where('school_id', Auth::user()->school_id)
                ->with('groupclassSections')
                ->whereNULL('parent_id')
                ->where('id', $request->class_id)
                ->first();

            $sectionIds = SmClassSection::where('class_id', '=', $request->class_id)
                ->where('school_id', Auth::user()->school_id)->get();

            $promote_sections = [];

            foreach ($sectionIds as $sectionId) {
                $promote_sections[] = SmSection::where('id', $sectionId->section_id)
                    ->withoutGlobalScope(StatusAcademicSchoolScope::class)
                    ->withoutGlobalScope(GlobalAcademicScope::class)
                    ->whereNull('parent_id')
                    ->first(['id', 'section_name']);
            }
        } else {
            $class = SmClass::find($request->class_id);
            if (teacherAccess()) {
                $sectionIds = SmAssignSubject::where('class_id', '=', $request->class_id)
                    ->where('teacher_id', Auth::user()->staff->id)
                    ->where('school_id', Auth::user()->school_id)
                    ->where('academic_id', SmAcademicYear::SINGLE_SCHOOL_API_ACADEMIC_YEAR())
                    ->select('class_id', 'section_id')
                    ->distinct(['class_id', 'section_id'])
                    ->withoutGlobalScope(StatusAcademicSchoolScope::class)
                    ->get();
            } else {
                $sectionIds = SmClassSection::where('class_id', $request->class_id)
                    ->withoutGlobalScope(StatusAcademicSchoolScope::class)
                    ->get();
            }

            $promote_sections = [];
            foreach ($sectionIds as $sectionId) {
                $promote_sections[] = SmSection::where('id', $sectionId->section_id)
                    ->withoutGlobalScope(StatusAcademicSchoolScope::class)
                    ->first(['id', 'section_name']);
            }
        }

        return $promote_sections;
    }
}
