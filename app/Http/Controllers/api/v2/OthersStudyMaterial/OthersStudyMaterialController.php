<?php

namespace App\Http\Controllers\api\v2\OthersStudyMaterial;

use Illuminate\Http\Request;
use App\Models\StudentRecord;
use App\SmTeacherUploadContent;
use App\Scopes\GlobalAcademicScope;
use App\Http\Controllers\Controller;
use App\Scopes\StatusAcademicSchoolScope;
use App\Http\Resources\AssignmentResource;

class OthersStudyMaterialController extends Controller
{
    public function othersDownload(Request $request)
    {
        $request->validate([
            'record_id' => 'required'
        ]);

        $record = StudentRecord::where('school_id', auth()->user()->school_id)->where('id', $request->record_id)->firstOrFail();

        $assignment = SmTeacherUploadContent::withoutGlobalScope(GlobalAcademicScope::class)
            ->with(['classes' => function ($q) {
                $q->withoutGlobalScope(StatusAcademicSchoolScope::class)->where('school_id', auth()->user()->school_id);
            }, 'sections' => function ($q) {
                $q->withoutGlobalScope(StatusAcademicSchoolScope::class)->where('school_id', auth()->user()->school_id);
            }])
            ->where('content_type', 'ot')
            ->whereNull('course_id')
            ->whereNull('chapter_id')
            ->where('available_for_all_classes', 1)
            ->whereNull('lesson_id')
            ->where('school_id', auth()->user()->school_id)
            ->where('academic_id', $record->academic_id)
            ->where(function ($que) use ($record) {
                return $que->where('class', $record->class_id)
                    ->orWhereNull('class');
            })
            ->where(function ($que) use ($record) {
                return $que->where('section', $record->section_id)
                    ->orWhereNull('section');
            })
            ->orderBy('id', 'DESC')
            ->get();

        $data = AssignmentResource::collection($assignment);

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
                'message' => 'Other material list'
            ];
        }

        return response()->json($response);
    }
}
