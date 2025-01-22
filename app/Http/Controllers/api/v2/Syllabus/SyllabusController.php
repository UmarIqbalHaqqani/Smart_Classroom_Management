<?php

namespace App\Http\Controllers\api\v2\Syllabus;

use Illuminate\Http\Request;
use App\Models\StudentRecord;
use App\SmTeacherUploadContent;
use App\Scopes\GlobalAcademicScope;
use App\Http\Controllers\Controller;

class SyllabusController extends Controller
{
    public function studentSyllabus(Request $request)
    {
        $record = StudentRecord::where('school_id', auth()->user()->school_id)
            ->where('id', $request->record_id)
            ->firstOrFail();

        $data = SmTeacherUploadContent::withoutGlobalScope(GlobalAcademicScope::class)
            ->where('content_type', 'sy')
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
            ->get()
            ->map(function ($value) {
                return [
                    'id'            => (int)$value->id,
                    'upload_date'   => (string)$value->upload_date,
                    'content_title' => (string)$value->content_title,
                    'description'   => (string)$value->description,
                    'upload_file'   => $value->upload_file ? (string)asset('/') . $value->upload_file : '',
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
                'message' => 'Syllabus list'
            ];
        }
        return response()->json($response);
    }
}
