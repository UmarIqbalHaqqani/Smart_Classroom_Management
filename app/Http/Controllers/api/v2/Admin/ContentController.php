<?php

namespace App\Http\Controllers\api\v2\Admin;

use App\User;
use Exception;

use App\SmStaff;
use App\SmAcademicYear;
use App\SmNotification;
use Illuminate\Http\Request;
use App\Models\StudentRecord;
use App\SmTeacherUploadContent;
use App\Traits\NotificationSend;
use App\Scopes\GlobalAcademicScope;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Notification;
use Modules\RolePermission\Entities\InfixRole;
use Modules\University\Entities\UnSemesterLabel;
use App\Notifications\StudyMeterialCreatedNotification;
use App\Http\Controllers\Admin\StudentInfo\SmStudentReportController;

class ContentController extends Controller
{
    use NotificationSend;
    public function uploadContents()
    {
        if (!teacherAccess()) {
            $uploadContents = SmTeacherUploadContent::where('academic_id', SmAcademicYear::SINGLE_SCHOOL_API_ACADEMIC_YEAR())
                ->where('school_id', auth()->user()->school_id)
                ->whereNullLms()
                ->orderBy('id', 'DESC')
                ->get()
                ->map(function ($row) {
                    if ($row->available_for_admin == 1) {
                        $avaiable = app('translator')->get('study.all_admins');
                    } elseif ($row->available_for_all_classes == 1) {
                        $avaiable = app('translator')->get('study.all_classes_student');
                    } elseif ($row->classes != "" && $row->sections != "") {
                        $avaiable = app('translator')->get('study.all_students_of') . " " . $row->classes->class_name . '->' . @$row->sections->section_name;
                    } elseif ($row->classes != "" && $row->section == null) {
                        $avaiable = app('translator')->get('study.all_students_of') . " " . $row->classes->class_name . '->' . app('translator')->get('study.all_sections');
                    } else {
                        if (moduleStatusCheck('University')) {
                            $avaiable = app('translator')->get('study.all_students_of') . " " . @$row->semesterLabel->name  . '(' . @$row->unSection->section_name . '-' . @$row->undepartment->name . ')';
                        } else {
                            $avaiable = app('translator')->get('study.all_students_of');
                        }
                    }

                    if (@$row->content_type == 'as')
                        $content_type = __('study.assignment');
                    elseif (@$row->content_type == 'st')
                        $content_type = __('study.study_material');
                    elseif (@$row->content_type == 'sy')
                        $content_type = __('study.syllabus');
                    else
                        $content_type = __('study.other_download');

                    return [
                        'id' => (int)$row->id,
                        'content_title' => (string)$row->content_title,
                        'content_type' => (string)$content_type,
                        'upload_date' => (string)$row->upload_date,
                        'available_for' => (string)$avaiable,
                        'upload_file' => $row->upload_file ? (string)asset($row->upload_file) : (string)null
                    ];
                });
        } else {
            $uploadContents = SmTeacherUploadContent::where(function ($q) {
                $q->where('created_by', auth()->user()->id);
            })->whereNullLms()->where('academic_id', SmAcademicYear::SINGLE_SCHOOL_API_ACADEMIC_YEAR())
                ->where('school_id', auth()->user()->school_id)
                ->orderBy('id', 'DESC')
                ->get()
                ->map(function ($row) {
                    if ($row->available_for_admin == 1) {
                        $avaiable = app('translator')->get('study.all_admins');
                    } elseif ($row->available_for_all_classes == 1) {
                        $avaiable = app('translator')->get('study.all_classes_student');
                    } elseif ($row->classes != "" && $row->sections != "") {
                        $avaiable = app('translator')->get('study.all_students_of') . " " . $row->classes->class_name . '->' . @$row->sections->section_name;
                    } elseif ($row->classes != "" && $row->section == null) {
                        $avaiable = app('translator')->get('study.all_students_of') . " " . $row->classes->class_name . '->' . app('translator')->get('study.all_sections');
                    } else {
                        if (moduleStatusCheck('University')) {
                            $avaiable = app('translator')->get('study.all_students_of') . " " . @$row->semesterLabel->name  . '(' . @$row->unSection->section_name . '-' . @$row->undepartment->name . ')';
                        } else {
                            $avaiable = app('translator')->get('study.all_students_of');
                        }
                    }

                    return [
                        'id' => (int)$row->id,
                        'content_title' => (string)$row->content_title,
                        'content_type' => (string)$row->content_type,
                        'upload_date' => (string)$row->upload_date,
                        'available_for' => (string)$avaiable,
                        'upload_file' => $row->upload_file ? (string)asset($row->upload_file) : (string)null
                    ];
                });
        }

        if (!$uploadContents) {
            $response = [
                'success' => false,
                'data'    => null,
                'message' => 'Operation failed'
            ];
        } else {
            $response = [
                'success' => true,
                'data'    => $uploadContents,
                'message' => 'Content list successful'
            ];
        }
        return response()->json($response);
    }


    public function storeContent(Request $request)
    {
        $maxFileSize = generalSetting()->file_size * 1024;
        if ($request->status != 'lmsStudyMaterial') {
            $this->validate($request, [
                'content_title' => "required|max:200",
                'content_type' => "required",
                'available_for' => 'required|array',
                'upload_date' => "required",
                'content_file' => "sometimes|nullable|mimes:pdf,doc,docx,jpg,jpeg,png,mp4,mp3,txt|max:" . $maxFileSize,
                'all_classes' => 'sometimes|nullable',
                'description' => 'sometimes|nullable',
                'source_url' => 'sometimes|nullable|url',
                'section'   => 'sometimes|nullable'
            ]);

            if ($request->available_for and is_array($request->available_for)) {
                if (array_search('admin', $request->available_for) || $request->all_classes == 'on') {
                    $this->validate($request, ['class' => 'sometimes|nullable']);
                } elseif (moduleStatusCheck('University') == false && array_search('student', $request->available_for) && $request->all_classes !== 'on') {
                    $this->validate($request, [
                        'class' => 'required'
                    ]);
                } elseif (moduleStatusCheck('University') && $request->un_session_id) {
                    $this->validate($request, [
                        'un_session_id' => 'required',
                        'un_department_id' => 'required',
                        'un_academic_id' => 'required',
                        'un_semester_id' => 'required',
                        'un_semester_label_id' => 'required'
                    ]);
                }
            }
        } else {
            $this->validate($request, [
                'content_title' => "required|max:200",
                'content_type' => "required",
                'available_for' => 'required|array',
                'upload_date' => "required",
                'content_file' => "sometimes|nullable|mimes:pdf,doc,docx,jpg,jpeg,png,mp4,mp3,txt|max:" . $maxFileSize,
                'description' => 'sometimes|nullable',
                'source_url' => 'sometimes|nullable|url',
                'section'   => 'sometimes|nullable'
            ]);
        }

        $student_ids = SmStudentReportController::classSectionStudent($request);
        $destination = 'public/uploads/upload_contents/';
        if ($request->section == "all") {
        } else {
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
            if ($request->status == 'lmsStudyMaterial') {
                if ($request->parent_course) {
                    $uploadContents->parent_course_id = $request->course_id;
                } else {
                    $uploadContents->course_id = $request->course_id;
                }
                $uploadContents->chapter_id = $request->chapter_id;
                $uploadContents->lesson_id = $request->lesson_id;
            }
            $uploadContents->created_by = auth()->user()->id;
            $uploadContents->save();
        }

        if ($request->content_type == 'as') {
            $purpose = 'assignment';
            $url = 'student-assignment';
        } elseif ($request->content_type == 'st') {
            $purpose = 'Study Material';
            $url = 'student-study-materia';
        } elseif ($request->content_type == 'sy') {
            $purpose = 'Syllabus';
            $url = 'student-syllabus';
        } elseif ($request->content_type == 'ot') {
            $purpose = 'Others Download';
            $url = 'student-others-download';
        }

        try {
            foreach ($request->available_for as $value) {
                if ($value == 'admin') {
                    $roles = InfixRole::where('id', '=', 1) /* ->where('id', '!=', 2)->where('id', '!=', 3)->where('id', '!=', 9) */->where(function ($q) {
                        $q->where('school_id', auth()->user()->school_id)->orWhere('type', 'System');
                    })->get();
                    foreach ($roles as $role) {
                        $staffs = SmStaff::where('role_id', $role->id)->where('school_id', auth()->user()->school_id)->get();
                        foreach ($staffs as $staff) {

                            $notification = new SmNotification;
                            $notification->user_id = $staff->user_id;
                            $notification->role_id = $role->id;
                            $notification->school_id = auth()->user()->school_id;
                            if (moduleStatusCheck('University')) {
                                $notification->un_academic_id = SmAcademicYear::SINGLE_SCHOOL_API_ACADEMIC_YEAR();
                            } else {
                                $notification->academic_id = SmAcademicYear::SINGLE_SCHOOL_API_ACADEMIC_YEAR();
                            }
                            if ($request->content_type == 'as') {
                                $notification->url = 'assignment-list';
                            } elseif ($request->content_type == 'st') {
                                $notification->url = 'study-metarial-list';
                            } elseif ($request->content_type == 'sy') {
                                $notification->url = 'syllabus-list';
                            } elseif ($request->content_type == 'ot') {
                                $notification->url = 'other-download-list';
                            }
                            $notification->date = date('Y-m-d');
                            $notification->message = $purpose . ' ' . app('translator')->get('common.uploaded');
                            $notification->save();


                            $user = User::find($notification->user_id);
                            Notification::send($user, new StudyMeterialCreatedNotification($notification));
                        }
                    }
                }
                if (($value == 'student') && ($request->status != 'lmsStudyMaterial')) {
                    if (isset($request->all_classes)) {
                        $records = StudentRecord::with('studentDetail', 'class', 'section')
                            ->where('is_promote', 0)
                            ->where('active_status', 1)
                            ->where('school_id', auth()->user()->school_id)
                            ->where('academic_id', SmAcademicYear::SINGLE_SCHOOL_API_ACADEMIC_YEAR())
                            ->distinct('student_id')
                            ->get();

                        foreach ($records as $record) {
                            $data['student_name'] = $record->studentDetail->full_name;
                            $data['assignment'] = $request->content_title;
                            $data['class'] = $record->class->class_name;
                            $data['section'] = $record->section->section_name;;
                            $data['subject'] = $purpose;
                            $data['url'] = $url;
                            if ($request->content_type == 'as') {
                                $this->sent_notifications('Assignment', (array)$record->studentDetail->user_id, $data, ['Student', 'Parent']);
                            } elseif ($request->content_type == 'sy') {
                                $this->sent_notifications('Syllabus', (array)$record->studentDetail->user_id, $data, ['Student', 'Parent']);
                            } elseif ($request->content_type == 'ot') {
                                $this->sent_notifications('Other_Downloads', (array)$record->studentDetail->user_id, $data, ['Student', 'Parent']);
                            }
                        }
                    } elseif ((!is_null($request->class)) &&   ($request->section == '')) {
                        $records = StudentRecord::with('studentDetail', 'class', 'section')
                            ->where('is_promote', 0)
                            ->where('active_status', 1)
                            ->whereIn('student_id', $student_ids)
                            ->where('school_id', auth()->user()->school_id)
                            ->where('academic_id', SmAcademicYear::SINGLE_SCHOOL_API_ACADEMIC_YEAR())
                            ->get();

                        foreach ($records as $record) {
                            $data['student_name'] = $record->studentDetail->full_name;
                            $data['assignment'] = $request->content_title;
                            $data['class'] = $record->class->class_name;
                            $data['section'] = $record->section->section_name;;
                            $data['subject'] = $purpose;
                            $data['url'] = $url;
                            if ($request->content_type == 'as') {
                                $this->sent_notifications('Assignment', (array)$record->studentDetail->user_id, $data, ['Student', 'Parent']);
                            } elseif ($request->content_type == 'sy') {
                                $this->sent_notifications('Syllabus', (array)$record->studentDetail->user_id, $data, ['Student', 'Parent']);
                            } elseif ($request->content_type == 'ot') {
                                $this->sent_notifications('Other_Downloads', (array)$record->studentDetail->user_id, $data, ['Student', 'Parent']);
                            }
                        }
                    } else {
                        $records = StudentRecord::with('studentDetail', 'class', 'section')
                            ->where('is_promote', 0)
                            ->where('active_status', 1)
                            ->whereIn('student_id', $student_ids)
                            ->where('school_id', auth()->user()->school_id)
                            ->where('academic_id', SmAcademicYear::SINGLE_SCHOOL_API_ACADEMIC_YEAR())
                            ->get();
                        foreach ($records as $record) {
                            $data['student_name'] = $record->studentDetail->full_name;
                            $data['assignment'] = $request->content_title;
                            $data['class'] = $record->class->class_name;
                            $data['section'] = $record->section->section_name;;
                            $data['subject'] = $purpose;
                            $data['url'] = $url;
                            if ($request->content_type == 'as') {
                                $this->sent_notifications('Assignment', (array)$record->studentDetail->user_id, $data, ['Student', 'Parent']);
                            } elseif ($request->content_type == 'sy') {
                                $this->sent_notifications('Syllabus', (array)$record->studentDetail->user_id, $data, ['Student', 'Parent']);
                            } elseif ($request->content_type == 'ot') {
                                $this->sent_notifications('Other_Downloads', (array)$record->studentDetail->user_id, $data, ['Student', 'Parent']);
                            }
                        }
                    }
                }
            }
        } catch (Exception $e) {
        }

        $data = SmTeacherUploadContent::where('id', $uploadContents->id)
            ->select('id', 'content_title', 'content_type', 'upload_file', 'description', 'upload_date', 'available_for_admin', 'available_for_all_classes', 'class', 'section')
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
                'data'    => [$data],
                'message' => 'The content uploaded successfully'
            ];
        }
        return response()->json($response);
    }


    public function deleteSingle(Request $request)
    {
        $request->validate([
            'content_id' => 'required|exists:sm_teacher_upload_contents,id'
        ], [
            'content_id.exists' => 'Invalid content'
        ]);

        $id =  $request->content_id;
        $uploadContent = SmTeacherUploadContent::withoutGlobalScope(GlobalAcademicScope::class)->where('id', $id)->where('school_id', auth()->user()->school_id)->first();
        if (checkAdmin() || $uploadContent->created_by == auth()->user()->id) {

            if (file_exists($uploadContent->upload_file)) {
                unlink($uploadContent->upload_file);
            }
            $delete = $uploadContent->delete();
            if (!$delete) {
                $response = [
                    'success' => false,
                    'data'    => null,
                    'message' => 'Operation failed'
                ];
            } else {
                $response = [
                    'success' => true,
                    'data'    => null,
                    'message' => 'The content deleted successfully'
                ];
            }
        }
        return response()->json($response);
    }
}
