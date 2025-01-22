<?php

namespace App\Http\Controllers\api\v2\Homework;

use App\User;
use Exception;
use App\SmStudent;
use App\SmHomework;
use App\SmAcademicYear;
use App\SmNotification;
use App\Scopes\SchoolScope;
use Illuminate\Http\Request;
use App\Models\StudentRecord;
use App\SmUploadHomeworkContent;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Scopes\StatusAcademicSchoolScope;
use App\Http\Resources\SmHomeworkResource;
use Illuminate\Support\Facades\Notification;
use App\Notifications\StudentHomeworkSubmitNotification;

class HomeworkController extends Controller
{
    public function adminTeacherhomework()
    {
        $all_homeworks = SmHomework::withoutGlobalScope(StatusAcademicSchoolScope::class)
            ->where('school_id', auth()->user()->school_id ?? app('school')->id)
            ->where('academic_id', SmAcademicYear::SINGLE_SCHOOL_API_ACADEMIC_YEAR())
            ->with(['subjects' => function ($q) {
                $q->withoutGlobalScopes([StatusAcademicSchoolScope::class])->select('id', 'subject_name');
            }])
            ->orderby('id', 'DESC')
            ->select('created_at', 'submission_date', 'evaluation_date', 'active_status', 'marks', 'subject_id');

        if (teacherAccess()) {
            $homeworkLists = $all_homeworks->where('created_by', auth()->user()->id)->get();
        } else {
            $homeworkLists = $all_homeworks->get();
        }

        $response = [
            'success' => true,
            'data'    => $homeworkLists
        ];
        return response()->json($response, 200);
    }


    public function studentHomework(Request $request)
    {
        $record = StudentRecord::findOrFail($request->record_id);
        $homeworkLists = SmHomework::withoutGlobalScope(StatusAcademicSchoolScope::class)->with(['subjects' => function ($q) {
            $q->withoutGlobalScopes([StatusAcademicSchoolScope::class])->where('school_id', auth()->user()->school_id);
        }, 'lmsHomeworkCompleted'])->where('school_id', auth()->user()->school_id)
            ->where(function ($que) use ($record) {
                return $que->where('class_id', $record->class_id)
                    ->orWhereNull('class_id');
            })
            ->where(function ($que) use ($record) {
                return $que->where('section_id', $record->section_id)
                    ->orWhereNull('section_id');
            })
            ->where('course_id', null)
            ->where('chapter_id', null)
            ->where('lesson_id', null)
            ->where('academic_id', SmAcademicYear::SINGLE_SCHOOL_API_ACADEMIC_YEAR())
            ->orderBy('id', 'DESC')
            ->get();

        $data['homeworkLists'] = SmHomeworkResource::collection($homeworkLists);

        $response = [
            'success' => true,
            'data'    => $data
        ];
        return response()->json($response);
    }

    public function parentHomework(Request $request)
    {
        $record = StudentRecord::where('school_id', auth()->user()->school_id)->where('id', $request->record_id)->firstOrFail();

        $homeworkLists = SmHomework::withoutGlobalScope(StatusAcademicSchoolScope::class)->with(['subjects' => function ($q) {
            $q->withoutGlobalScope(StatusAcademicSchoolScope::class)
                ->where('school_id', auth()->user()->school_id)
                ->select('id', 'subject_name');
        }])->select('created_at', 'submission_date', 'evaluation_date', 'active_status', 'marks', 'subject_id')
            ->where('school_id', auth()->user()->school_id)
            ->where(function ($que) use ($record) {
                return $que->where('class_id', $record->class_id)
                    ->orWhereNull('class_id');
            })
            ->where(function ($que) use ($record) {
                return $que->where('section_id', $record->section_id)
                    ->orWhereNull('section_id');
            })
            ->where('course_id', '=', null)
            ->where('chapter_id', '=', null)
            ->where('lesson_id', '=', null)
            ->where('academic_id', SmAcademicYear::SINGLE_SCHOOL_API_ACADEMIC_YEAR())
            ->where('school_id', auth()->user()->school_id)
            ->get();

        $response = [
            'success' => true,
            'data'    => $homeworkLists
        ];
        return response()->json($response, 200);
    }

    public function studentHomeworkView(Request $request)
    {
        $request->validate([
            'class_id'      => 'required',
            'section_id'    => 'required',
            'homework_id'   => 'required',
        ]);

        $homeworkDetails = SmHomework::withoutGlobalScope(StatusAcademicSchoolScope::class)
            ->where('school_id', auth()->user()->school_id)
            ->where('class_id', $request->class_id)
            ->where('section_id', $request->section_id)
            ->where('id', $request->homework_id)
            ->first();
        $response = [
            'success' => true,
            'data'    => $homeworkDetails
        ];
        return response()->json($response, 200);
    }

    public function studentHomeworkFileDownload(Request $request)
    {
        $homeworkDetails = SmHomework::withoutGlobalScope(StatusAcademicSchoolScope::class)
            ->where('school_id', auth()->user()->school_id)
            ->where('id', $request->homework_id)
            ->firstOrFail();
        $file_path = asset('/') . $homeworkDetails->file;

        // return response()->download($file_path);
        if (!$homeworkDetails->file) {
            $response = [
                'success' => false,
                'data'    => (string)null,
                'message' => 'Operation failed'
            ];
        } else {
            $response = [
                'success' => true,
                'data'    => (string)$file_path,
                'message' => 'Student homewrok file'
            ];
        }
        return response()->json($response);
    }

    public function uploadHomeworkContent(Request $request)
    {
        if ($request->file('files') == "") {
            $response = [
                'success' => false,
                'data'    => null,
                'message' => 'No file uploaded.',
            ];
            return response()->json($response, 422);
        }

        $user = Auth::user();
        $student_detail = SmStudent::withoutGlobalScope(SchoolScope::class)
            ->where('school_id', auth()->user()->school_id)
            ->where('user_id', $user->id)
            ->first();
        $data = [];
        foreach ($request->file('files') as $key => $file) {
            $fileName = md5($file->getClientOriginalName() . time()) . "." . $file->getClientOriginalExtension();
            $file->move('public/uploads/homeworkcontent/', $fileName);
            $fileName = 'public/uploads/homeworkcontent/' . $fileName;
            $data[$key] = $fileName;
        }
        $all_filename = json_encode($data);
        $content = new SmUploadHomeworkContent();
        $content->file = $all_filename;
        $content->student_id = $student_detail->id;
        $content->homework_id = $request->id;
        $content->school_id = Auth::user()->school_id;
        $content->academic_id = SmAcademicYear::SINGLE_SCHOOL_API_ACADEMIC_YEAR();
        $content->save();

        $homework_info = SmHomeWork::withoutGlobalScope(StatusAcademicSchoolScope::class)
            ->where('school_id', auth()->user()->school_id)
            ->where('id', $request->id)
            ->first();
        $teacher_info = User::find($homework_info->created_by);

        try {
            $notification = new SmNotification();
            $notification->user_id = $teacher_info->id;
            $notification->role_id = $teacher_info->role_id;
            $notification->date = date('Y-m-d');
            $notification->message = Auth::user()->student->full_name . ' ' . app('translator')->get('homework.submitted_homework');
            $notification->school_id = Auth::user()->school_id;
            $notification->academic_id = SmAcademicYear::SINGLE_SCHOOL_API_ACADEMIC_YEAR();
            $notification->save();

            $user = User::find($teacher_info->id);
            Notification::send($user, new StudentHomeworkSubmitNotification($notification));
        } catch (Exception $e) {
            //
        }

        $response = [
            'success' => true,
            'data'    => null,
            'message' => 'Operation Successful.',
        ];
        return response()->json($response, 200);
    }
}
