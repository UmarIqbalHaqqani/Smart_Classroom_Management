<?php

namespace App\Http\Controllers\api\v2\Assignment;

use App\SmStudent;
use App\SmNotification;
use Illuminate\Http\Request;
use App\Models\StudentRecord;
use App\SmTeacherUploadContent;
use App\Scopes\GlobalAcademicScope;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Scopes\StatusAcademicSchoolScope;
use App\Http\Resources\AssignmentResource;

class AssignmentController extends Controller
{
    public function studentAssignment(Request $request)
    {
        $record = StudentRecord::where('school_id', auth()->user()->school_id)
            ->where('id', $request->record_id)
            ->firstOrFail();

            $assignment = SmTeacherUploadContent::withoutGlobalScope(GlobalAcademicScope::class)
            ->with('classes', 'sections')
            ->where('content_type', 'as')
            ->whereNull('course_id')
            ->whereNull('chapter_id')
            ->whereNull('lesson_id')
            ->where('academic_id', $record->academic_id)
            ->where('available_for_admin', 0)
            ->where('school_id', auth()->user()->school_id)
            ->where(function ($query) use ($record) {
                $query->where('available_for_all_classes', 1)
                      ->orWhere(function ($q) use ($record) {
                          $q->where(function ($que) use ($record) {
                              $que->where('class', $record->class_id)
                                  ->orWhereNull('class');
                          })
                          ->where(function ($que) use ($record) {
                              $que->where('section', $record->section_id)
                                  ->orWhereNull('section');
                          });
                      });
            })
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
                'message' => 'Assignment list'
            ];
        }
        return response()->json($response);
    }

    public function studentAssignmentFileDownload($id)
    {
        $homeworkDetails = SmTeacherUploadContent::select('upload_file')->find($id);
        if (isset($homeworkDetails) && !empty($homeworkDetails->upload_file)) {
            $file_path = asset('/') . $homeworkDetails->upload_file;
            // return response()->download($file_path);
            $response = [
                'success' => true,
                'data'    => $file_path,
                'message' => 'Operation successful',
            ];
            return response()->json($response, 200);
        } else {
            $response = [
                'success' => false,
                'data'    => null,
                'message' => 'File not available',
            ];
            return response()->json($response, 401);
        }
    }

    public function uploadContentView(Request $request)
    {
        $data = SmTeacherUploadContent::withoutGlobalScope(GlobalAcademicScope::class)
            ->with(['classes' => function ($q) {
                $q->withoutGlobalScope(StatusAcademicSchoolScope::class)->where('school_id', auth()->user()->school_id)->select('id', 'class_name');
            }, 'sections' => function ($q) {
                $q->withoutGlobalScope(StatusAcademicSchoolScope::class)->where('school_id', auth()->user()->school_id)->select('id', 'section_name');
            }])
            ->where('school_id', auth()->user()->school_id)
            ->where('id', $request->teacher_upload_content_id)
            ->select('id', 'upload_date', 'content_title', 'available_for_admin', 'available_for_all_classes', 'upload_file', 'class', 'section')
            ->first();

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
                    'message' => 'Content detail'
                ];
            }
            return response()->json($response);
    }
}
