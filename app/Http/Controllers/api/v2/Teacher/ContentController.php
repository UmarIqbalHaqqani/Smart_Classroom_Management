<?php

namespace App\Http\Controllers\api\v2\Teacher;

use App\SmAcademicYear;
use Illuminate\Http\Request;
use App\SmTeacherUploadContent;
use App\Scopes\GlobalAcademicScope;

use App\Http\Controllers\Controller;
use App\Http\Resources\v2\Teacher\Content\ContentListResource;
use Illuminate\Validation\Rule;

class ContentController extends Controller
{
    public function contentList()
    {
        $uploadContents = SmTeacherUploadContent::withoutGlobalScope(GlobalAcademicScope::class)
            ->where('academic_id', SmAcademicYear::SINGLE_SCHOOL_API_ACADEMIC_YEAR())
            ->where('school_id', auth()->user()->school_id)
            ->whereNullLms()
            ->latest()
            ->get();
        if(teacherAccess() || $uploadContents->created_by == auth()->user()->id){
            $data = ContentListResource::collection($uploadContents);
        }
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
                'message' => 'Content list'
            ];
        }
        return response()->json($response);
    }

    public function doeleteContent(Request $request)
    {
        $request->validate([
            'content_id' => ['required', Rule::exists('sm_teacher_upload_contents', 'id')->where('school_id', auth()->user()->school_id)]
        ]);

        $id =  $request->content_id;

        $uploadContent = SmTeacherUploadContent::withoutGlobalScope(GlobalAcademicScope::class)
            ->where('id', $id)
            ->where('school_id', auth()->user()->school_id)
            ->first();

        if (teacherAccess() || $uploadContent->created_by == auth()->user()->id) {
            if (file_exists($uploadContent->upload_file)) {
                unlink($uploadContent->upload_file);
            }
            $data = $uploadContent->delete();
        }
        if (!$data) {
            $response = [
                'success' => false,
                'data'    => null,
                'message' => 'Operation failed'
            ];
        } else {
            $response = [
                'success' => true,
                'data'    => null,
                'message' => 'Content deleted successfully'
            ];
        }
        return response()->json($response);
    }

    public function storeContent(Request $request)
    {
        $maxFileSize = generalSetting()->file_size * 1024;
        $destination = 'public/uploads/upload_contents/';

        $this->validate($request, [
            'content_title' => "required|max:200",
            'content_type' => "required",
            'available_for' => 'required|array',
            'upload_date' => "required",
            'content_file' => "sometimes|nullable|mimes:pdf,doc,docx,jpg,jpeg,png,mp4,mp3,txt|max:" . $maxFileSize,
            'description' => 'sometimes|nullable',
            'source_url' => 'sometimes|nullable|url',
            'class'   => 'sometimes|nullable',
            'section'   => 'sometimes|nullable'
        ]);

        if (teacherAccess()) {
            $uploadContents = new SmTeacherUploadContent();
            $uploadContents->content_title = $request->content_title;
            $uploadContents->content_type = $request->content_type;
            $uploadContents->school_id = auth()->user()->school_id;
            $uploadContents->academic_id = SmAcademicYear::SINGLE_SCHOOL_API_ACADEMIC_YEAR();
            foreach ($request->available_for as $value) {
                if ($value == 'admin') {
                    $uploadContents->available_for_admin = 1;
                }

                if ($value == 'student') {
                    if (isset($request->all_classes)) {
                        $uploadContents->available_for_all_classes = 1;
                    } else {
                        $uploadContents->class = $request->class;
                        $uploadContents->section = $request->section;
                    }
                }
            }
            $uploadContents->upload_date = date('Y-m-d', strtotime($request->upload_date));
            $uploadContents->description = $request->description;
            $uploadContents->source_url = $request->source_url;
            $uploadContents->upload_file = fileUpload($request->content_file, $destination);
            $uploadContents->created_by = auth()->user()->id;
            $uploadContents->save();

            $data = [
                'id'                        => (int)$uploadContents->id,
                'content_title'             => (string)$uploadContents->content_title,
                'content_type'              => (string)$uploadContents->content_type,
                'available_for_admin'       => (bool)$uploadContents->available_for_admin,
                'available_for_all_classes' => (bool)$uploadContents->available_for_all_classes,
                'upload_date'               => (string)$uploadContents->upload_date,
                'content_file'              => $uploadContents->upload_file ? (string)asset($uploadContents->upload_file) : (string)null,
                'class'                     => (int)$uploadContents->class,
                'section'                   => (int)$uploadContents->section
            ];
        }

        if (!$data) {
            $response = [
                'success' => false,
                'data'    => null,
                'message' => 'Operation failed'
            ];
        } else {
            $response = [
                'success' => true,
                'data'    => [$data],
                'message' => 'The content created successfully'
            ];
        }
        return response()->json($response);
    }
}
