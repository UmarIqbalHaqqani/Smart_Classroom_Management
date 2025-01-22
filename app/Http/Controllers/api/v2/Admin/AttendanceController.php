<?php

namespace App\Http\Controllers\api\v2\Admin;
use App\Http\Controllers\Admin\SystemSettings\SmSystemSettingController;

use App\SmClass;
use App\SmStaff;
use App\SmParent;
use App\SmSection;
use App\SmStudent;
use App\SmSubject;
use Carbon\Carbon;
use App\Models\User;
use App\SmAcademicYear;
use App\SmClassSection;
use App\SmNotification;
use App\SmAssignSubject;
use App\Scopes\SchoolScope;
use App\SmStudentAttendance;
use App\SmSubjectAttendance;
use Illuminate\Http\Request;
use App\Models\StudentRecord;
use Illuminate\Http\Response;
use App\Traits\NotificationSend;
use App\Scopes\AcademicSchoolScope;
use App\Scopes\GlobalAcademicScope;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Scopes\ActiveStatusSchoolScope;
use App\Scopes\StatusAcademicSchoolScope;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Notification;
use App\Notifications\FlutterAppNotification;
use Modules\University\Entities\UnSubjectAssignStudent;
use App\Http\Resources\v2\Admin\AttendanceStudentResource;
use App\Http\Resources\v2\Admin\AttendanceStudentListResource;
use App\Http\Resources\v2\Admin\SubjectAttendanceStudentResource;

class AttendanceController extends Controller
{
    use NotificationSend;
    public function classList()
    {
        $data = SmClass::withoutGlobalScopes([StatusAcademicSchoolScope::class])
            ->select('id', 'class_name')
            ->where('active_status', 1)
            ->where('academic_id', SmAcademicYear::SINGLE_SCHOOL_API_ACADEMIC_YEAR())
            ->where('school_id', auth()->user()->school_id)->get();

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
                'message' => 'Class list'
            ];
        }
        return response()->json($response);
    }

    public function sectionList(Request $request)
    {
        if ($request->parent) {
            $sectionIds = SmClassSection::withoutGlobalScope(GlobalAcademicScope::class)
                ->where('class_id', $request->class_id)
                ->where('school_id', auth()->user()->school_id)
                ->get();

            $promote_sections = [];
            foreach ($sectionIds as $sectionId) {
                $promote_sections[] = SmSection::withoutGlobalScope(StatusAcademicSchoolScope::class)
                    ->where('school_id', auth()->user()->school_id)
                    ->where('id', $sectionId->section_id)
                    ->whereNull('parent_id')
                    ->first(['id', 'section_name']);
            }
        } else {
            if (teacherAccess()) {
                $sectionIds = SmAssignSubject::withoutGlobalScope(StatusAcademicSchoolScope::class)
                    ->where('teacher_id', auth()->user()->staff->id)
                    ->where('school_id', auth()->user()->school_id)
                    ->where('academic_id', SmAcademicYear::SINGLE_SCHOOL_API_ACADEMIC_YEAR())
                    ->where('class_id', $request->class_id)
                    ->select('class_id', 'section_id')
                    ->distinct(['class_id', 'section_id'])
                    ->get();
            } else {
                $sectionIds = SmClassSection::withoutGlobalScope(StatusAcademicSchoolScope::class)
                    ->where('school_id', auth()->user()->school_id)
                    ->where('class_id', $request->class_id)
                    ->get();
            }

            $promote_sections = [];
            foreach ($sectionIds as $sectionId) {
                $promote_sections[] = SmSection::withoutGlobalScope(StatusAcademicSchoolScope::class)
                    ->where('school_id', auth()->user()->school_id)
                    ->where('id', $sectionId->section_id)
                    ->first(['id', 'section_name']);
            }
        }

        if (!$promote_sections) {
            $response = [
                'success' => false,
                'data'    => [],
                'message' => 'Operation failed'
            ];
        } else {
            $response = [
                'success' => true,
                'data'    => $promote_sections,
                'message' => 'Section list'
            ];
        }
        return response()->json($response);
    }

    public function subjectList(Request $request)
    {
        try {
            $staff_info = SmStaff::withoutGlobalScope(ActiveStatusSchoolScope::class)
                ->where('school_id', auth()->user()->school_id)
                ->where('user_id', auth()->user()->id)
                ->first();
    
            if (!$staff_info) {
                return response()->json([
                    'success' => false,
                    'data'    => null,
                    'message' => 'Staff information not found'
                ]);
            }
    
            if (teacherAccess()) {
                $subject_all = SmAssignSubject::withoutGlobalScope(StatusAcademicSchoolScope::class)
                    ->where('school_id', auth()->user()->school_id)
                    ->where('class_id', $request->class)
                    ->where('section_id', $request->section)
                    ->where('teacher_id', $staff_info->id)
                    ->distinct('subject_id')
                    ->get();
            } else {
                $subject_all = SmAssignSubject::withoutGlobalScope(StatusAcademicSchoolScope::class)
                    ->where('school_id', auth()->user()->school_id)
                    ->where('class_id', $request->class)
                    ->where('section_id', $request->section)
                    ->distinct('subject_id')
                    ->get();
            }
    
            $subjects = [];
            foreach ($subject_all as $allSubject) {
                $subject = SmSubject::withoutGlobalScope(StatusAcademicSchoolScope::class)
                    ->where('school_id', auth()->user()->school_id)
                    ->where('id', $allSubject->subject_id)
                    ->first(['id', 'subject_name']);
                if ($subject) {
                    $subjects[] = $subject;
                }
            }
    
            if (empty($subjects)) {
                return response()->json([
                    'success' => true,
                    'data'    => [],
                    'message' => 'No subjects found, or may not be assigned to you'
                ]);
            }
    
            return response()->json([
                'success' => true,
                'data'    => $subjects,
                'message' => 'Subject list retrieved successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'data'    => null,
                'message' => 'An error occurred: ' . $e->getMessage()
            ], 500);
        }
    }   

    public function studentSearch(Request $request)
    {
        $this->validate($request, [
            'class_id' => "required",
            'section_id' => "required",
            'attendance_date' => "required|date_format:Y-m-d"
        ], [
            "attendance_date.date_format" => "The attendance date does not match the format 'Year-Month-Date'.",
        ]);

        $students = StudentRecord::with([
            'studentDetail' => function ($q) use ($request) {
                $q->with(['DateWiseAttendances' => function ($q) use ($request) {
                    $q->withoutGlobalScope(AcademicSchoolScope::class)->where('attendance_date', $request->attendance_date);
                }]);
            }, 'studentAttendance' => function ($q) use ($request) {
                $q->withOutGlobalScope(AcademicSchoolScope::class);
            }
        ])
            ->when($request->class_id, function ($query) use ($request) {
                $query->where('class_id', $request->class_id);
            })
            ->whereHas('studentDetail', function ($q) {
                $q->where('active_status', 1);
            })
            ->when($request->section_id, function ($query) use ($request) {
                $query->where('section_id', $request->section_id);
            })
            ->where('academic_id', SmAcademicYear::SINGLE_SCHOOL_API_ACADEMIC_YEAR())
            ->where('school_id', auth()->user()->school_id)
            ->get()->sortBy('roll_no');

        $holiday = SmStudentAttendance::withoutGlobalScope(AcademicSchoolScope::class)
            ->where('class_id', $request->class_id)
            ->where('section_id', $request->section_id)
            ->where('school_id', auth()->user()->school_id)
            ->where('academic_id', SmAcademicYear::SINGLE_SCHOOL_API_ACADEMIC_YEAR())
            ->where('attendance_date', $request->attendance_date)
            ->where('attendance_type', 'H')->exists();

        if ($students->isEmpty()) {
            $response = [
                'success' => true,
                'data'    => null,
                'message' => 'No Result Found',
            ];
            return response()->json($response, 200);
        }
        $attendance_type = $students[0]['studentDetail']['DateWiseAttendances'] != null ? $students[0]['studentDetail']['DateWiseAttendances']['attendance_type'] : '';
        if ($attendance_type != "" && $attendance_type == "H") {
            $data['submitted_message'] = __('student.attendance_already_submitted_as_holiday');
        } elseif ($attendance_type != "" && $attendance_type != "H") {
            $data['submitted_message'] = __('student.attendance_already_submitted');
        }

        $class_name = SmClass::withoutGlobalScope(StatusAcademicSchoolScope::class)
            ->where('id', $request->class_id)
            ->where('school_id', auth()->user()->school_id)
            ->first();

        $section_name = SmSection::withoutGlobalScope(StatusAcademicSchoolScope::class)
            ->where('id', $request->section_id)
            ->where('school_id', auth()->user()->school_id)
            ->first();

        $data['class_name'] = $class_name ? (string)$class_name->class_name : '';
        $data['section_name'] =  $section_name ? (string)$section_name->section_name : '';
        $data['class_id'] = $class_name ? (int)$class_name->id : (int)'';
        $data['section_id'] =  $section_name ? (int)$section_name->id : (int)'';
        $data['date'] = (string)$request->attendance_date;
        $data['holiday'] = (bool)$holiday;
        $data['students'] = AttendanceStudentResource::collection($students);
        $data['status'] = 'Present: P, Late: L, Absent: A, Holiday: H, Half Day: F';

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
                'message' => 'Search student attendance'
            ];
        }
        return response()->json($response);
    }

    public function studentAttendanceHoliday(Request $request)
    {
        $request->validate([
            'purpose' => 'nullable|in:mark,unmark',
            'class_id' => 'required',
            'section_id' => 'required',
            'attendance_date' => 'required|date_format:Y-m-d'
        ]);
        $studentRecords = StudentRecord::where('class_id', $request->class_id)
            ->where('section_id', $request->section_id)
            ->where('academic_id', SmAcademicYear::SINGLE_SCHOOL_API_ACADEMIC_YEAR())
            ->where('school_id', auth()->user()->school_id)
            ->get();
        if ($studentRecords->isEmpty()) {
            $response = [
                'success' => false,
                'data'    => null,
                'message' => 'No Result Found',
            ];
            return response()->json($response, 200);
        }
        $holiday = SmStudentAttendance::withoutGlobalScope(AcademicSchoolScope::class)
            ->where('class_id', $request->class_id)
            ->where('section_id', $request->section_id)
            ->where('school_id', auth()->user()->school_id)
            ->where('academic_id', SmAcademicYear::SINGLE_SCHOOL_API_ACADEMIC_YEAR())
            ->where('attendance_date', $request->attendance_date)
            ->where('attendance_type', 'H')->exists();

        if ($request->purpose == "mark" && $holiday) {
            $response = [
                'success' => false,
                'data'    => null,
                'message' => 'Already marked as holiday'
            ];
            return response()->json($response, Response::HTTP_UNPROCESSABLE_ENTITY);
        } else {
            foreach ($studentRecords as $record) {
                $attendance = SmStudentAttendance::withoutGlobalScope(AcademicSchoolScope::class)
                    ->where('student_id', $record->student_id)
                    ->where('attendance_date', date('Y-m-d', strtotime($request->attendance_date)))
                    ->when(!moduleStatusCheck('University'), function ($query) use ($request) {
                        $query->where('class_id', $request->class_id);
                    })->when(!moduleStatusCheck('University'), function ($query) use ($request) {
                        $query->where('section_id', $request->section_id);
                    })
                    ->where('academic_id', SmAcademicYear::SINGLE_SCHOOL_API_ACADEMIC_YEAR())
                    ->where('student_record_id', $record->id)
                    ->where('school_id', auth()->user()->school_id)
                    ->first();

                if (!empty($attendance)) {
                    $attendance->delete();
                }
                if ($request->purpose == "mark") {
                    $attendance = new SmStudentAttendance();
                    $attendance->attendance_type    = "H";
                    $attendance->notes              = "Holiday";
                    $attendance->attendance_date    = date('Y-m-d', strtotime($request->attendance_date));
                    $attendance->student_id         = $record->student_id;
                    $attendance->student_record_id  = $record->id;
                    $attendance->class_id           = $record->class_id;
                    $attendance->section_id         = $record->section_id;
                    $attendance->academic_id        = SmAcademicYear::SINGLE_SCHOOL_API_ACADEMIC_YEAR();
                    $attendance->school_id          = auth()->user()->school_id;
                    $attendance->save();

                    $compact['holiday_date'] = date('Y-m-d', strtotime($request->attendance_date));
                    try {
                        @send_sms($record->student->mobile, 'holiday', $compact);
                        @send_sms(@$record->student->parents->guardians_mobile, 'holiday', $compact);

                        // futter notification
                        $messege = "";
                        $student = SmStudent::find($record->student_id);
                        if ($student) {
                            $messege = app('translator')->get('student.Your_teacher_has_marked_holiday_in_the_attendance_on ', ['date' => dateconvert($attendance->attendance_date)]);
                            $notification = new SmNotification();
                            $notification->user_id = $student->user_id;
                            $notification->role_id = 2;
                            $notification->date = date('Y-m-d');
                            $notification->message = $messege;
                            $notification->school_id = auth()->user()->school_id;
                            $notification->academic_id = SmAcademicYear::SINGLE_SCHOOL_API_ACADEMIC_YEAR();
                            $notification->save();

                            if ($student->user) {
                                $title = app('translator')->get('student.attendance_notication');
                            
                                $notificationData = [
                                    'id'    => $student->user->id,
                                    'title' => $title,
                                    'body'  => $notification->message,
                                ];
                                $systemSettingController = new SmSystemSettingController();
                                $systemSettingController->flutterNotificationApi(new Request($notificationData));
                            }

                            $parent = SmParent::find($student->parent_id);
                            if ($parent) {
                                $messege = app('translator')->get('student.Your_child_is_marked_holiday_in_the_attendance_on_date', ['date' => dateConvert($attendance->attendance_date), 'student_name' => $student->full_name . "'s"]);
                                $notification = new SmNotification();
                                $notification->user_id = $parent->user_id;
                                $notification->role_id = 3;
                                $notification->date = date('Y-m-d');
                                $notification->message = $messege;
                                $notification->school_id = auth()->user()->school_id;
                                $notification->academic_id = SmAcademicYear::SINGLE_SCHOOL_API_ACADEMIC_YEAR();
                                $notification->save();

                                if ($parent->parent_user) {
                                    $title = app('translator')->get('student.attendance_notication');
                                    $notificationData = [
                                        'id'    => $parent->parent_user->id,
                                        'title' => $title,
                                        'body'  => $notification->message,
                                    ];
                                
                                    $systemSettingController = new SmSystemSettingController();
                                    $systemSettingController->flutterNotificationApi(new Request($notificationData));                                }
                            }

                            $compact['holiday_date'] = date('Y-m-d', strtotime($request->attendance_date));
                            @send_sms($record->student->mobile, 'holiday', $compact);
                        }
                        // end
                    } catch (\Throwable $th) {
                        //throw $th;
                    }
                }
            }
        }
        
        $resMsg = ucwords($request->purpose ?? 'unmark');

        $response = [
            'success' => true,
            'data'    => null,
            'message' => "$resMsg as holiday",
        ];
        return response()->json($response, 200);
    }

    public function search(Request $request)
    {
        $data = [];
        $input['attendance_date'] = $request->attendance_date;
        $input['class'] = $request->class;
        $input['subject'] = $request->subject;
        $input['section'] = $request->section;

        /* $classes = SmClass::get();
        $sections = SmClassSection::with('sectionName')->where('class_id', $input['class'])->get();
        $subjects = SmAssignSubject::with('subject')->where('class_id', $input['class'])->where('section_id', $input['section'])
            ->get(); */

        $students = StudentRecord::with('studentDetail', 'studentDetail.DateSubjectWiseAttendances')
            ->where('class_id', $input['class'])
            ->where('section_id', $input['section'])
            ->where('academic_id', SmAcademicYear::SINGLE_SCHOOL_API_ACADEMIC_YEAR())
            ->where('school_id', auth()->user()->school_id)->get();

        $holiday = SmStudentAttendance::withoutGlobalScope(AcademicSchoolScope::class)
            ->where('class_id', $request->class)
            ->where('section_id', $request->section)
            ->where('school_id', auth()->user()->school_id)
            ->where('academic_id', SmAcademicYear::SINGLE_SCHOOL_API_ACADEMIC_YEAR())
            ->where('attendance_date', $request->attendance_date)
            ->where('attendance_type', 'H')->exists();

        if ($students->isEmpty()) {
            $response = [
                'success'  => false,
                'data' => null,
                'message' => 'No Result Found',
            ];
            return response()->json($response, 401);
        }

        $attendance_type = $students[0]['studentDetail']['DateSubjectWiseAttendances'] != null  ? $students[0]['studentDetail']['DateSubjectWiseAttendances']['attendance_type'] : '';

        $data['class_name'] = SmClass::withoutGlobalScopes([StatusAcademicSchoolScope::class])
            ->where('id', $request->class)
            ->where('school_id', auth()->user()->school_id)
            ->first()->class_name;

        $data['section_name'] = SmSection::withoutGlobalScopes([StatusAcademicSchoolScope::class])
            ->where('id', $request->section)
            ->where('school_id', auth()->user()->school_id)
            ->first()->section_name;

        $data['subject_name'] = SmSubject::withoutGlobalScopes([StatusAcademicSchoolScope::class])
            ->where('id', $request->subject)
            ->where('school_id', auth()->user()->school_id)
            ->first()->subject_name;

        $data['subject_id'] = (int)$request->subject;
        $data['class_id'] = (int)$request->class;
        $data['section_id'] = (int)$request->section;

        if ($attendance_type != "" && $attendance_type == "H") {
            $data['submitted_message'] = __('student.attendance_already_submitted_as_holiday');
        } elseif ($attendance_type != "" && $attendance_type != "H") {
            $data['submitted_message'] = __('student.attendance_already_submitted');
        }
        $data['date'] = $input['attendance_date'];
        $data['holiday'] = $holiday;
        $data['students'] = SubjectAttendanceStudentResource::collection($students);
        // $data['status'] = 'Present: P, Late: L, Absent: A, Holiday: H, Half Day: F';

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
                'message' => 'Searched attendance'
            ];
        }
        return response()->json($response);
    }



    public function studentSearchAttend(Request $request)
    {
        $request->validate([
            'class' => 'required|exists:sm_class_sections,class_id',
            'section' => 'required|exists:sm_class_sections,section_id'
        ]);
        $students = StudentRecord::where('class_id', $request->class)
            ->where('section_id', $request->section)
            ->when($request->name, function ($q) use ($request) {
                $q->whereHas('studentDetail', function ($q) use ($request) {
                    return $q->where(function ($q) use ($request) {
                        return $q->where('first_name', 'like', '%' . $request->name . '%')
                            ->orWhere('last_name', 'like', '%' . $request->name . '%')
                            ->orWhere('full_name', 'like', '%' . $request->name . '%');
                    });
                });
            })
            ->when($request->roll_no, function ($q2) use ($request) {
                $q2->whereHas('studentDetail', function ($q2) use ($request) {
                    return $q2->where(function ($q2) use ($request) {
                        return $q2->where('roll_no', 'like', '%' . $request->roll_no . '%');
                    });
                });
            })
            ->where('academic_id', SmAcademicYear::SINGLE_SCHOOL_API_ACADEMIC_YEAR())
            ->where('school_id', auth()->user()->school_id)->get();

        $data['students'] = AttendanceStudentListResource::collection($students);

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
                'message' => 'Student attendance detail'
            ];
        }
        return response()->json($response);
    }

    public function studentAttendanceReportSearch(Request $request)
    {
        $request->validate([
            'student_attendance_id' => 'required|exists:student_records,id',
            'month' => 'nullable|required_with:year|date_format:m',
            'year' => 'nullable|required_with:month|date_format:Y'
        ]);

        $student_id = SmStudent::where('id', $request->student_attendance_id)->first();

        $record = StudentRecord::where('student_id', $student_id->id)
            ->where('school_id', auth()->user()->school_id)
            ->first();

        if ($request->year && $request->month) {
            $year = $request->year;
            $month = sprintf('%02d', $request->month);
        } else {
            $year = date('Y');
            $month  = date('m');
        }

        $attendances = SmStudentAttendance::withoutGlobalScope(AcademicSchoolScope::class)->where('student_id', $record->student_id)
            ->whereYear('attendance_date', $year)
            ->whereMonth('attendance_date', $month)
            ->where('academic_id', SmAcademicYear::SINGLE_SCHOOL_API_ACADEMIC_YEAR())->where('school_id', auth()->user()->school_id)
            ->where('student_record_id', $record->id)
            ->where('school_id', $record->school_id)
            ->select('attendance_type', 'attendance_date')
            ->get();
        $data['attendances'] = $attendances;
        $current_day = date('d');
        $data['current_day'] = $year . '-' . $month . '-' . $current_day;
        $data['status'] = 'Present: P, Late: L, Absent: A, Holiday: H, Half Day: F';
        $data['P'] = $attendances->where('attendance_type', 'P')->count();
        $data['L'] = $attendances->where('attendance_type', 'L')->count();
        $data['A'] = $attendances->where('attendance_type', 'A')->count();
        $data['H'] = $attendances->where('attendance_type', 'H')->count();
        $data['F'] = $attendances->where('attendance_type', 'F')->count();
        $data['class'] = $record->class->class_name;
        $data['section'] = $record->section->section_name;
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
                'message' => 'Attendance report'
            ];
        }
        return response()->json($response);
    }

    public function subjectWiseStudents(Request $request)
    {
        $data = [];
        $input['attendance_date'] = $request->attendance_date;
        $input['class'] = $request->class_id;
        $input['subject'] = $request->subject_id;
        $input['section'] = $request->section_id;

        // $classes = SmClass::get();
        // $sections = SmClassSection::with('sectionName')->where('class_id', $input['class'])->get();
        // $subjects = SmAssignSubject::with('subject')->where('class_id', $input['class'])->where('section_id', $input['section'])->get();

        $students = StudentRecord::with('studentDetail', 'studentDetail.DateSubjectWiseAttendances')
            ->where('class_id', $input['class'])
            ->where('section_id', $input['section'])
            ->when($request->name, function ($q) use ($request) {
                $q->whereHas('studentDetail', function ($q) use ($request) {
                    return $q->where(function ($q) use ($request) {
                        return $q->where('first_name', 'like', '%' . $request->name . '%')
                            ->orWhere('last_name', 'like', '%' . $request->name . '%')
                            ->orWhere('full_name', 'like', '%' . $request->name . '%');
                    });
                });
            })
            ->when($request->roll_no, function ($q2) use ($request) {
                $q2->whereHas('studentDetail', function ($q2) use ($request) {
                    return $q2->where(function ($q2) use ($request) {
                        return $q2->where('roll_no', 'like', '%' . $request->roll_no . '%');
                    });
                });
            })
            ->where('academic_id', SmAcademicYear::SINGLE_SCHOOL_API_ACADEMIC_YEAR())
            ->where('school_id', auth()->user()->school_id)->get();

        if ($students->isEmpty()) {
            $response = [
                'success'  => false,
                'data' => null,
                'message' => 'No Result Found',
            ];
            return response()->json($response, 401);
        }

        $students[0]['studentDetail']['DateSubjectWiseAttendances'] != null  ? $students[0]['studentDetail']['DateSubjectWiseAttendances']['attendance_type'] : '';

        // $data['class_name'] = SmClass::find($request->class_id)->class_name;
        // $data['section_name'] = SmSection::find($request->section_id)->section_name;
        $subject = SmSubject::withoutGlobalScope(StatusAcademicSchoolScope::class)->select('id', 'subject_name')->find($request->subject_id);
        $data['subject_name_id'] = (int)$subject->id;

        // $data['date'] = $input['attendance_date'];
        $data['students'] = AttendanceStudentResource::collection($students);
        // $data['status'] = 'Present: P, Late: L, Absent: A, Holiday: H, Half Day: F';

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
                'message' => 'Student list'
            ];
        }
        return response()->json($response);
    }

    public function studentSubjectAttendanceSearch(Request $request)
    {
        $request->validate([
            'record_id' => 'required',
            'month' => "nullable|required_with:year|date_format:m",
            'year' => "nullable|required_with:month|date_format:Y"
        ]);
        $record = StudentRecord::where('id', $request->record_id)->where('school_id', auth()->user()->school_id)->first();
        if ($request->year && $request->month) {
            $year = $request->year;
            $month = sprintf('%02d', $request->month);
        } else {
            $year = date('Y');
            $month  = date('m');
        }
        $days = cal_days_in_month(CAL_GREGORIAN, $month, $year);
        /* $days2 = '';
        if ($month != 1) {
            $days2 = cal_days_in_month(CAL_GREGORIAN, $month - 1, $year);
        } else {
            $days2 = cal_days_in_month(CAL_GREGORIAN, $month, $year);
        } */

        $current_day = date('d');

        $all_attendances = SmSubjectAttendance::where('school_id', auth()->user()->school_id)
            ->whereMonth('attendance_date', $month)
            ->whereYear('attendance_date', $year)
            ->when($request->subject_id, function ($query) use ($request) {
                $query->whereNotNull('subject_id')->where('subject_id', $request->subject_id);
            })
            ->where('student_record_id', $record->id)
            ->select('subject_id', 'attendance_type', 'attendance_date')
            ->distinct('attendance_date')->get();
        /* if ($request->subject_id) {
            $all_attendances = SmSubjectAttendance::where('school_id', auth()->user()->school_id)
            ->whereMonth('attendance_date', $month)
            ->whereYear('attendance_date', $year)
            ->where('subject_id', $request->subject_id)
            ->where('student_record_id', $record->id)
            ->select('attendance_type', 'attendance_date')
            ->distinct('attendance_date')->get();
        } else {
            $all_attendances = SmSubjectAttendance::whereNotNull('subject_id')
                ->where('attendance_date', 'like', '%' . $year . '-' . $month . '%')
                ->where('student_record_id', $record->id)
                ->select('attendance_type', 'attendance_date', 'subject_id')
                ->distinct('attendance_date')->get();
        } */

        $attendances = $all_attendances;
        $total_pres = 0;
        $total_late = 0;
        $toal_abs = 0;
        $total_half = 0;
        $total_holiday = 0;

        foreach ($all_attendances as $atd) {
            if ($atd->attendance_type == "P") {
                $total_pres += 1;
            } elseif ($atd->attendance_type == "L") {
                $total_late += 1;
            } elseif ($atd->attendance_type == "A") {
                $toal_abs += 1;
            } elseif ($atd->attendance_type == "F") {
                $total_half += 1;
            } elseif ($atd->attendance_type == "H") {
                $total_holiday += 1;
            }
        }

        $data['class_name'] = (string)@SmClass::withoutGlobalScope(StatusAcademicSchoolScope::class)->find($record->class_id)->class_name;
        $data['section_name'] = (string)@SmSection::withoutGlobalScope(StatusAcademicSchoolScope::class)->find($record->section_id)->section_name;
        $data['subject_name'] = (string)@SmSubject::withoutGlobalScope(StatusAcademicSchoolScope::class)->find($request->subject_id)->subject_name;


        $data['attendances'] = $attendances;
        $data['days'] = $days;
        $data['total_present'] = (int)$total_pres;
        $data['total_absent'] = (int)$toal_abs;
        $data['total_late'] = (int)$total_late;
        $data['total_half_day'] = (int)$total_half;
        $data['total_holiday'] = (int)$total_holiday;
        // $data['year'] = $year;
        // $data['month'] = $month;
        // $data['current_day'] = $current_day;
        $data['current_day'] = $year . '-' . $month . '-' . $current_day;
        $data['status'] = 'Present: P, Late: L, Absent: A, Holiday: H, Half Day: F';

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
                'message' => 'Subject wise attendance'
            ];
        }
        return response()->json($response);
    }

    public function studentAttendanceStore(Request $request)
    {
        foreach ($request->get('record_id', []) as $key => $record) {
            $attendance = SmStudentAttendance::withoutGlobalScope(AcademicSchoolScope::class)
                ->where('student_id', $request->student_id[$key])
                ->where('attendance_date', date('Y-m-d', strtotime($request->date)))
                ->where('academic_id', SmAcademicYear::SINGLE_SCHOOL_API_ACADEMIC_YEAR())->first();

            if ($attendance) {
                $attendance->delete();
            }

            $attendance = new SmStudentAttendance();
            $attendance->student_record_id = $record;
            $attendance->student_id = $request->student_id[$key];
            $attendance->class_id = $request->class;
            $attendance->section_id = $request->section;
            $attendance->school_id = auth()->user()->school_id;
            if (isset($request->mark_holiday)) {
                $attendance->attendance_type = "H";
            } else {
                $attendance->attendance_type = $request->attendance_type[$key];
                $attendance->notes = $request->note[$key];
            }
            $attendance->attendance_date = date('Y-m-d', strtotime($request->date));
            $attendance->save();
        }

        $response = [
            'success' => true,
            'data'    => null,
            'message' => 'Attendance stored successfully',
        ];
        return response()->json($response, 200);
    }

    public function subjectWiseAttendanceStore(Request $request)
    {
        foreach ($request->get('record_id', []) as $record_id => $student) {

            $attendance = SmSubjectAttendance::where('student_id', $request->student_id[$record_id])
                ->where('subject_id', $request->subject_id)
                ->where('attendance_date', date('Y-m-d', strtotime($request->date)))
                ->where('class_id', $request->class)
                ->where('section_id', $request->section)
                ->where('student_record_id', $request->record_id[$record_id])
                ->where('academic_id', SmAcademicYear::SINGLE_SCHOOL_API_ACADEMIC_YEAR())
                ->where('school_id', auth()->user()->school_id)
                ->first();
            if ($attendance != "") {
                $attendance->delete();
            }
            $attendance = new SmSubjectAttendance();
            $attendance->student_record_id = $student;
            $attendance->subject_id = $request->subject_id;
            $attendance->student_id = $request->student_id[$record_id];
            $attendance->class_id = $request->class;
            $attendance->section_id = $request->section;
            $attendance->attendance_type = $request->attendance_type[$record_id];
            $attendance->notes = $request->note[$record_id];
            $attendance->school_id = auth()->user()->school_id;
            $attendance->academic_id = SmAcademicYear::SINGLE_SCHOOL_API_ACADEMIC_YEAR();
            $attendance->attendance_date = date('Y-m-d', strtotime($request->date));
            $attendance->save();

            $data['class_id'] = gv($student, 'class');
            $data['section_id'] = gv($student, 'section');
            $data['subject'] = @$attendance->subject->subject_name;
            // $records = $this->studentRecordInfo($data['class_id'], $data['section_id'])->pluck('studentDetail.user_id');
            // $this->sent_notifications('Subject_Wise_Attendance', $records, $data, ['Student', 'Parent']);
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
                'message' => 'Attendance stored successfully'
            ];
        }
        return response()->json($response);
    }

    public function subjectHolidayStore(Request $request)
    {
        $request->validate([
            'purpose' => 'nullable|in:mark,unmark',
            'class_id' => 'required',
            'section_id' => 'required',
            'subject_id' => 'required',
            'attendance_date' => 'required|date_format:Y-m-d'
        ]);
        $active_students = SmStudent::withoutGlobalScope(SchoolScope::class)->where('active_status', 1)
            ->where('academic_id', SmAcademicYear::SINGLE_SCHOOL_API_ACADEMIC_YEAR())
            ->where('school_id', auth()->user()->school_id)
            ->get()->pluck('id')->toArray();
        $students = StudentRecord::where('class_id', $request->class_id)
            ->where('section_id', $request->section_id)
            ->whereIn('student_id', $active_students)
            ->where('academic_id', SmAcademicYear::SINGLE_SCHOOL_API_ACADEMIC_YEAR())
            ->where('school_id', auth()->user()->school_id)
            ->get();

        if ($students->isEmpty()) {
            $response = [
                'success' => false,
                'data'    => null,
                'message' => 'No Result Found',
            ];
            return response()->json($response, 422);
        }

        $holiday = SmSubjectAttendance::where('subject_id', $request->subject_id)
            ->where('attendance_date', date('Y-m-d', strtotime($request->attendance_date)))
            ->where('attendance_type', 'H')
            ->where('class_id', $request->class_id)->where('section_id', $request->section_id)
            ->where('academic_id', SmAcademicYear::SINGLE_SCHOOL_API_ACADEMIC_YEAR())
            ->where('school_id', auth()->user()->school_id)
            ->exists();

        if ($request->purpose == "mark" && $holiday) {
            $response = [
                'success' => false,
                'data'    => null,
                'message' => 'Already marked as holiday'
            ];
            return response()->json($response, Response::HTTP_UNPROCESSABLE_ENTITY);
        } else {
            if ($request->purpose == "mark") {
                foreach ($students as $record) {
                    $attendance = SmSubjectAttendance::where('student_id', $record->student_id)
                        ->where('subject_id', $request->subject_id)
                        ->where('attendance_date', date('Y-m-d', strtotime($request->attendance_date)))
                        ->where('class_id', $request->class_id)->where('section_id', $request->section_id)
                        ->where('student_record_id', $record->id)
                        ->where('academic_id', SmAcademicYear::SINGLE_SCHOOL_API_ACADEMIC_YEAR())
                        ->where('school_id', auth()->user()->school_id)
                        ->first();

                    if (!empty($attendance)) {
                        $attendance->delete();
                        $attendance = new SmSubjectAttendance();
                        $attendance->attendance_type    = "H";
                        $attendance->notes              = "Holiday";
                        $attendance->attendance_date    = date('Y-m-d', strtotime($request->attendance_date));
                        $attendance->student_id         = $record->student_id;
                        $attendance->subject_id         = $request->subject_id;
                        $attendance->student_record_id  = $record->id;
                        $attendance->class_id           = $record->class_id;
                        $attendance->section_id         = $record->section_id;
                        $attendance->academic_id        = SmAcademicYear::SINGLE_SCHOOL_API_ACADEMIC_YEAR();
                        $attendance->school_id          = auth()->user()->school_id;
                        $attendance->save();
                    } else {
                        $attendance = new SmSubjectAttendance();
                        $attendance->attendance_type    = "H";
                        $attendance->notes              = "Holiday";
                        $attendance->attendance_date    = date('Y-m-d', strtotime($request->attendance_date));
                        $attendance->student_id         = $record->student_id;
                        $attendance->subject_id         = $request->subject_id;
                        $attendance->student_record_id  = $record->id;
                        $attendance->class_id           = $record->class_id;
                        $attendance->section_id         = $record->section_id;
                        $attendance->academic_id        = SmAcademicYear::SINGLE_SCHOOL_API_ACADEMIC_YEAR();
                        $attendance->school_id          = auth()->user()->school_id;
                        $attendance->save();
                    }

                    //notification
                    try {
                        $messege = "";
                        $date = dateConvert($attendance->attendance_date);
                        $student = SmStudent::find($record->student_id);
                        $subject = SmSubject::withoutGlobalScope(StatusAcademicSchoolScope::class)
                            ->where('id', $request->subject_id)
                            ->where('school_id', auth()->user()->school_id)->first();
                        $subject_name = $subject->subject_name;

                        if ($student) {
                            $messege = app('translator')->get('student.Your_teacher_has_marked_holiday_in_the_attendance_on_subject', ['date' => $date, 'subject_name' => $subject_name]);

                            $notification = new SmNotification();
                            $notification->user_id = $student->user_id;
                            $notification->role_id = 2;
                            $notification->date = date('Y-m-d');
                            $notification->message = $messege;
                            $notification->school_id = auth()->user()->school_id;
                            $notification->academic_id = SmAcademicYear::SINGLE_SCHOOL_API_ACADEMIC_YEAR();
                            $notification->save();

                            if ($student->user) {
                                $title = app('translator')->get('student.attendance_notication');
                                $notificationData = [
                                    'id'    => $student->user->id,
                                    'title' => $title,
                                    'body'  => $notification->message,
                                ];
                            
                                $systemSettingController = new SmSystemSettingController();
                                $systemSettingController->flutterNotificationApi(new Request($notificationData));
                            }

                            // for parent user
                            $parent = SmParent::find($student->parent_id);
                            if ($parent) {
                                $messege = app('translator')->get('student.Your_child_is_marked_holiday_in_the_attendance_on_subject', ['date' => $date, 'student_name' => $student->full_name . "'s", 'subject_name' => $subject_name]);

                                $notification = new SmNotification();
                                $notification->user_id = $parent->user_id;
                                $notification->role_id = 3;
                                $notification->date = date('Y-m-d');
                                $notification->message = $messege;
                                $notification->school_id = auth()->user()->school_id;
                                $notification->academic_id = SmAcademicYear::SINGLE_SCHOOL_API_ACADEMIC_YEAR();
                                $notification->save();


                                $user = User::find($notification->user_id);
                                if ($user) {
                                    $title = app('translator')->get('student.attendance_notication');
                                    $notificationData = [
                                        'id'    => $parent->parent_user->id,
                                        'title' => $title,
                                        'body'  => $notification->message,
                                    ];
                                    $systemSettingController = new SmSystemSettingController();
                                    $systemSettingController->flutterNotificationApi(new Request($notificationData));                                }
                            }
                        }
                    } catch (\Exception $e) {
                        //throw $e;
                    }
                }
            } else {
                foreach ($students as $record) {
                    $attendance = SmSubjectAttendance::where('student_id', $record->student_id)
                        ->where('subject_id', $request->subject_id)
                        ->where('attendance_date', date('Y-m-d', strtotime($request->attendance_date)))
                        ->where('class_id', $request->class_id)->where('section_id', $request->section_id)
                        ->where('student_record_id', $record->id)
                        ->where('academic_id', SmAcademicYear::SINGLE_SCHOOL_API_ACADEMIC_YEAR())
                        ->where('school_id', auth()->user()->school_id)
                        ->first();
                    if (!empty($attendance)) {
                        $attendance->delete();
                    }
                }
            }
        }

        $resMsg = ucwords($request->purpose ?? 'unmark');
        $response = [
            'success' => true,
            'data'    => null,
            'message' => "$resMsg as holiday"
        ];
        return response()->json($response);
    }
}
