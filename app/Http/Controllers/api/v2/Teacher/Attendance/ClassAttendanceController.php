<?php

namespace App\Http\Controllers\api\v2\Teacher\Attendance;

use App\Http\Controllers\Admin\SystemSettings\SmSystemSettingController;
use App\SmClass;
use App\SmStaff;
use App\SmParent;
use App\SmSection;
use App\SmStudent;
use App\SmSubject;
use App\SmAcademicYear;
use App\SmClassSection;
use App\SmNotification;
use App\SmAssignSubject;
use App\Scopes\SchoolScope;
use App\SmStudentAttendance;
use Illuminate\Http\Request;
use App\Models\StudentRecord;
use App\Traits\NotificationSend;
use App\Scopes\AcademicSchoolScope;
use App\Scopes\GlobalAcademicScope;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Scopes\ActiveStatusSchoolScope;
use App\Scopes\StatusAcademicSchoolScope;
use Illuminate\Support\Facades\Notification;
use App\Notifications\FlutterAppNotification;
use App\Http\Resources\v2\Admin\AttendanceStudentResource;
use App\Http\Resources\v2\Teacher\Attendance\ClassAttendanceStudentListResource;

class ClassAttendanceController extends Controller
{
    use NotificationSend;


    public function classes()
    {
        if (teacherAccess()) {
            $teacherId = SmStaff::withoutGlobalScope(ActiveStatusSchoolScope::class)
                ->where('school_id', auth()->user()->school_id)
                ->where('user_id', auth()->id())->first()->id;

            $data = SmClass::withoutGlobalScopes([StatusAcademicSchoolScope::class, GlobalAcademicScope::class])
                ->where('school_id', auth()->user()->school_id)->whereHas('subjects', function ($subject) use ($teacherId) {
                    $subject->where('school_id', auth()->user()->school_id)
                        ->where('teacher_id', $teacherId);
                })
                ->select(['id', 'class_name'])->get();
        }

        if (!isset($data)) {
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

    public function sections(Request $request)
    {
        if (teacherAccess()) {
            $sectionIds = SmClassSection::withoutGlobalScope(GlobalAcademicScope::class)
                ->where('class_id', $request->class_id)
                ->where('school_id', auth()->user()->school_id)
                ->get();
            $data = [];
            foreach ($sectionIds as $sectionId) {
                $data[] = SmSection::withoutGlobalScope(StatusAcademicSchoolScope::class)
                    ->where('id', $sectionId->section_id)
                    ->whereNull('parent_id')
                    ->first(['id', 'section_name']);
            }
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
                'message' => 'Section list for class'
            ];
        }
        return response()->json($response);
    }

    public function subjects(Request $request)
    {
        if (teacherAccess()) {
            $staff_info = SmStaff::withoutGlobalScope(ActiveStatusSchoolScope::class)
                ->where('user_id', auth()->user()->id)
                ->where('school_id', auth()->user()->school_id)->first();
            if (teacherAccess()) {
                $subject_all = SmAssignSubject::withoutGlobalScope(StatusAcademicSchoolScope::class)
                    ->where('class_id', $request->class)
                    ->where('section_id', $request->section)
                    ->where('teacher_id', $staff_info->id)
                    ->where('school_id', auth()->user()->school_id)
                    ->distinct('subject_id')
                    ->get();
            }

            foreach ($subject_all as $allSubject) {
                $data[] = SmSubject::withoutGlobalScope(StatusAcademicSchoolScope::class)
                    ->where('id', $allSubject->subject_id)
                    ->where('school_id', auth()->user()->school_id)
                    ->first(['id', 'subject_name']);
            }
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
                'message' => 'Subject list'
            ];
        }
        return response()->json($response);
    }

    public function students(Request $request)
    {
        $this->validate($request, [
            'class' => "required",
            'section' => "required",
            'attendance_date' => "required"
        ]);

        $students = StudentRecord::with(['studentDetail' => function ($q) {
            $q->withoutGlobalScope(SchoolScope::class)
                ->with(['DateWiseAttendances' => function ($q) {
                    $q->withoutGlobalScope(AcademicSchoolScope::class)
                        ->where('school_id', auth()->user()->school_id);
                }])
                ->where('school_id', auth()->user()->school_id);
        }, 'studentDetail.DateWiseAttendances' => function ($q) {
            $q->withoutGlobalScopes([SchoolScope::class, AcademicSchoolScope::class])
                ->where('school_id', auth()->user()->school_id);
        }])
            ->when($request->class, function ($query) use ($request) {
                $query->where('class_id', $request->class);
            })
            ->whereHas('studentDetail', function ($q) {
                $q->withoutGlobalScope(SchoolScope::class)->where('school_id', auth()->user()->school_id)->where('active_status', 1);
            })

            ->when($request->section, function ($query) use ($request) {
                $query->where('section_id', $request->section);
            })->where('academic_id', SmAcademicYear::SINGLE_SCHOOL_API_ACADEMIC_YEAR())
            ->where('school_id', auth()->user()->school_id)
            ->get()->sortBy('roll_no');


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
        $selected['class_id'] = $request->class;
        $selected['section_id'] = $request->section;

        $class_name = SmClass::withoutGlobalScopes([StatusAcademicSchoolScope::class])
            ->where('school_id', auth()->user()->school_id)
            ->where('id', $request->class)->first();
        $section_name = SmSection::withoutGlobalScope(StatusAcademicSchoolScope::class)
            ->where('school_id', auth()->user()->school_id)
            ->where('id', $request->section)->first();
        $data['class_name'] = $class_name ? $class_name->class_name : '';
        $data['section_name'] =  $section_name ? $section_name->section_name : '';
        $data['class_id'] = $class_name ? $class_name->id : '';
        $data['section_id'] =  $section_name ? $section_name->id : '';
        $data['date'] = $request->attendance_date;
        $data['students'] = ClassAttendanceStudentListResource::collection($students);
        $data['status'] = 'Present: P, Late: L, Absent: A, Holiday: H, Half Day: F';

        /* $attendancee = SmStudentAttendance::withoutGlobalScope(AcademicSchoolScope::class)
            ->with(['studentInfo' => function ($q) {
                $q->withoutGlobalScope(SchoolScope::class)
                    ->where('school_id', auth()->user()->school_id);
            }])
            ->where('school_id', auth()->user()->school_id)
            ->where('class_id', $request->class)
            ->where('section_id', $request->section)
            ->where('attendance_date', date('Y-m-d', strtotime($request->attendance_date)))
            ->get();
        */

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

    public function storeAttendance(Request $request)
    {
        if (teacherAccess()) {
            foreach ($request->get('record_id', []) as $key => $record) {
                $attendance = SmStudentAttendance::withoutGlobalScope(AcademicSchoolScope::class)->where('student_id', $request->student_id[$key])
                    ->where('attendance_date', date('Y-m-d', strtotime($request->date)))
                    ->where('school_id', auth()->user()->school_id)
                    ->where('academic_id', SmAcademicYear::SINGLE_SCHOOL_API_ACADEMIC_YEAR())
                    ->first();

                if ($attendance != null) {
                    $attendance->delete();
                }

                $attendance = new SmStudentAttendance();
                $attendance->student_record_id = $record;
                $attendance->student_id = $request->student_id[$key];
                $attendance->class_id = $request->class;
                $attendance->section_id = $request->section;
                if (isset($request->mark_holiday) && $request->mark_holiday == 'mark') {
                    $attendance->attendance_type = "H";
                } else {
                    $attendance->attendance_type = $request->attendance_type[$key];
                    $attendance->notes = $request->note[$key];
                }
                $attendance->attendance_date = date('Y-m-d', strtotime($request->date));
                $attendance->school_id = auth()->user()->school_id;
                $attendance->academic_id = SmAcademicYear::SINGLE_SCHOOL_API_ACADEMIC_YEAR();
                $data = $attendance->save();
            }
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
                'message' => 'Attendance store successfully'
            ];
        }
        return response()->json($response);
    }

    public function markHoliday(Request $request)
    {

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
        foreach ($studentRecords as $record) {
            $attendance = SmStudentAttendance::where('student_id', $record->student_id)
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
                $attendance->attendance_type = "H";
                $attendance->notes = "Holiday";
                $attendance->attendance_date = date('Y-m-d', strtotime($request->attendance_date));
                $attendance->student_id = $record->student_id;
                $attendance->student_record_id = $record->id;
                $attendance->class_id = $record->class_id;
                $attendance->section_id = $record->section_id;
                $attendance->academic_id = SmAcademicYear::SINGLE_SCHOOL_API_ACADEMIC_YEAR();
                $attendance->school_id = auth()->user()->school_id;

                $attendance->save();

                $compact['holiday_date'] = date('Y-m-d', strtotime($request->attendance_date));
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
                            $systemSettingController->flutterNotificationApi(new Request($notificationData));
                        }
                    }

                    $compact['holiday_date'] = date('Y-m-d', strtotime($request->attendance_date));
                    @send_sms($record->student->mobile, 'holiday', $compact);
                }
                // end
            }
        }

        $response = [
            'success' => true,
            'data'    => null,
            'message' => 'Operation Successful',
        ];
        return response()->json($response, 200);
    }

    public function holiday(Request $request)
    {
        $studentRecords = StudentRecord::where('academic_id', SmAcademicYear::SINGLE_SCHOOL_API_ACADEMIC_YEAR())
            ->where('school_id', auth()->user()->school_id)
            ->where('class_id', $request->class_id)
            ->where('section_id', $request->section_id)
            ->get();
        if ($studentRecords->isEmpty()) {
            $response = [
                'success' => false,
                'data'    => null,
                'message' => 'No Result Found',
            ];
            return response()->json($response, 200);
        }
        foreach ($studentRecords as $record) {
            $attendance = SmStudentAttendance::withoutGlobalScope(AcademicSchoolScope::class)
                ->where('school_id', auth()->user()->school_id)
                ->where('attendance_date', date('Y-m-d', strtotime($request->attendance_date)))
                ->when(!moduleStatusCheck('University'), function ($query) use ($request) {
                    $query->where('class_id', $request->class_id);
                })->when(!moduleStatusCheck('University'), function ($query) use ($request) {
                    $query->where('section_id', $request->section_id);
                })
                ->where('academic_id', SmAcademicYear::SINGLE_SCHOOL_API_ACADEMIC_YEAR())
                ->where('student_id', $record->student_id)
                ->where('student_record_id', $record->id)
                ->first();

            if ($attendance != null) {
                $attendance->delete();
            }
            if ($request->purpose == "mark") {
                $attendance = new SmStudentAttendance();
                $attendance->attendance_type = "H";
                $attendance->notes = "Holiday";
                $attendance->attendance_date = date('Y-m-d', strtotime($request->attendance_date));
                $attendance->student_id = $record->student_id;
                $attendance->student_record_id = $record->id;
                $attendance->class_id = $record->class_id;
                $attendance->section_id = $record->section_id;
                $attendance->academic_id = SmAcademicYear::SINGLE_SCHOOL_API_ACADEMIC_YEAR();
                $attendance->school_id = auth()->user()->school_id;

                $attendance->save();

                $compact['holiday_date'] = date('Y-m-d', strtotime($request->attendance_date));
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
                            $systemSettingController->flutterNotificationApi(new Request($notificationData));
                        }
                    }


                    $compact['holiday_date'] = date('Y-m-d', strtotime($request->attendance_date));
                    @send_sms($record->student->mobile, 'holiday', $compact);
                }
                // end

            }
        }

        $response = [
            'success' => true,
            'data'    => null,
            'message' => 'Operation Successful'
        ];
        return response()->json($response, 200);
    }
}
