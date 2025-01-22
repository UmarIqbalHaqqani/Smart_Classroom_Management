<?php

namespace App\Http\Controllers\api\v2\Teacher\Attendance;

use App\Http\Controllers\Admin\SystemSettings\SmSystemSettingController;
use App\SmClass;
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
use App\SmSubjectAttendance;
use Illuminate\Http\Request;
use App\Models\StudentRecord;
use App\Traits\NotificationSend;
use App\Scopes\GlobalAcademicScope;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Scopes\StatusAcademicSchoolScope;
use Illuminate\Support\Facades\Notification;
use App\Notifications\FlutterAppNotification;
use App\Http\Resources\v2\Teacher\Attendance\SubjectWiseSearchResource;

class SubjectWiseAttendanceController extends Controller
{
    use NotificationSend;

    public function searchAttendance(Request $request)
    {
        $data = [];
        $input['attendance_date'] = $request->attendance_date;
        $input['class'] = $request->class;
        $input['subject'] = $request->subject;
        $input['section'] = $request->section;

        // $classes = SmClass::get();
        // $sections = SmClassSection::with('sectionName')->where('class_id', $input['class'])->get();
        // $subjects = SmAssignSubject::with('subject')->where('class_id', $input['class'])->where('section_id', $input['section'])
        //     ->get();

        $students = StudentRecord::with('studentDetail', 'studentDetail.DateSubjectWiseAttendances')
            ->where('class_id', $input['class'])
            ->where('section_id', $input['section'])
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

        $attendance_type = $students[0]['studentDetail']['DateSubjectWiseAttendances'] != null  ? $students[0]['studentDetail']['DateSubjectWiseAttendances']['attendance_type'] : '';

        $data['class_name'] = SmClass::withoutGlobalScope(StatusAcademicSchoolScope::class)
            ->where('school_id', auth()->user()->school_id)
            ->where('id', $request->class)->first()->class_name;
        $data['section_name'] = SmSection::withoutGlobalScope(StatusAcademicSchoolScope::class)
            ->where('school_id', auth()->user()->school_id)
            ->where('id', $request->section)->first()->section_name;
        $data['subject_name'] = SmSubject::withoutGlobalScope(StatusAcademicSchoolScope::class)
            ->where('school_id', auth()->user()->school_id)
            ->where('id', $request->subject)->first()->subject_name;
        $data['subject_id'] = (int)$request->subject;
        $data['class_id'] = (int)$request->class;
        $data['section_id'] = (int)$request->section;

        if ($attendance_type != "" && $attendance_type == "H") {
            $data['submitted_message'] = __('student.attendance_already_submitted_as_holiday');
        } elseif ($attendance_type != "" && $attendance_type != "H") {
            $data['submitted_message'] = __('student.attendance_already_submitted');
        }
        $data['date'] = $input['attendance_date'];
        $data['students'] = SubjectWiseSearchResource::collection($students);
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
                'message' => 'Search result for subject attendance'
            ];
        }
        return response()->json($response);
    }

    public function storeAttendance(Request $request)
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

            if ($attendance != null) {
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
            $r = $attendance->save();

            $data['class_id'] = gv($student, 'class');
            $data['section_id'] = gv($student, 'section');
            $data['subject'] = @$attendance->subject->subject_name;
            $records = $this->studentRecordInfo($data['class_id'], $data['section_id'])->pluck('studentDetail.user_id');
            // $this->sent_notifications('Subject_Wise_Attendance', $records, $data, ['Student', 'Parent']);
        }
        $response = [
            'success' => true,
            'data'    => null,
            'message' => 'Student attendance been submitted successfully',
        ];
        return response()->json($response, 200);
    }

    public function holiday(Request $request)
    {
        $records = StudentRecord::where('school_id', auth()->user()->school_id)
            ->where('academic_id', SmAcademicYear::SINGLE_SCHOOL_API_ACADEMIC_YEAR())
            ->where('section_id', $request->section_id)
            ->where('class_id', $request->class_id)
            ->get();

        if (!$records) {
            $response = [
                'success' => false,
                'data'    => null,
                'message' => 'No Result Found',
            ];
            return response()->json($response, 422);
        }
        if ($request->purpose == "mark") {
            foreach ($records as $record) {
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
                    $attendance->attendance_type = "H";
                    $attendance->notes = "Holiday";
                    $attendance->attendance_date = date('Y-m-d', strtotime($request->attendance_date));
                    $attendance->student_id = $record->student_id;
                    $attendance->subject_id = $request->subject_id;
                    $attendance->student_record_id = $record->id;
                    $attendance->class_id = $record->class_id;
                    $attendance->section_id = $record->section_id;
                    $attendance->academic_id = SmAcademicYear::SINGLE_SCHOOL_API_ACADEMIC_YEAR();
                    $attendance->school_id = auth()->user()->school_id;
                    $attendance->save();
                } else {
                    $attendance = new SmSubjectAttendance();
                    $attendance->attendance_type = "H";
                    $attendance->notes = "Holiday";
                    $attendance->attendance_date = date('Y-m-d', strtotime($request->attendance_date));
                    $attendance->student_id = $record->student_id;
                    $attendance->subject_id = $request->subject_id;

                    $attendance->student_record_id = $record->id;
                    $attendance->class_id = $record->class_id;
                    $attendance->section_id = $record->section_id;

                    $attendance->academic_id = SmAcademicYear::SINGLE_SCHOOL_API_ACADEMIC_YEAR();
                    $attendance->school_id = auth()->user()->school_id;
                    $attendance->save();
                }


                //notification

                $messege = "";
                $date = dateConvert($attendance->attendance_date);

                $student = SmStudent::withoutGlobalScope(SchoolScope::class)
                    ->where('school_id', auth()->user()->school_id)
                    ->where('id', $record->student_id)->first();
                $subject = SmSubject::withoutGlobalScope(StatusAcademicSchoolScope::class)
                    ->where('school_id', auth()->user()->school_id)
                    ->where('id', $request->subject_id)->first();
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
                            $systemSettingController->flutterNotificationApi(new Request($notificationData));                        }
                    }
                }
            }
            $response = [
                'success' => true,
                'data'    => null,
                'message' => 'The subject is marked as holiday',
            ];
        } else {
            foreach ($records as $record) {
                $attendance = SmSubjectAttendance::where('student_id', $record->student_id)
                    ->where('subject_id', $request->subject_id)
                    ->where('attendance_date', date('Y-m-d', strtotime($request->attendance_date)))
                    ->where('class_id', $request->class_id)->where('section_id', $request->section_id)
                    ->where('student_record_id', $record->id)
                    ->where('academic_id', SmAcademicYear::SINGLE_SCHOOL_API_ACADEMIC_YEAR())
                    ->where('school_id', auth()->user()->school_id)
                    ->first();
                if ($attendance != null) {
                    $attendance->delete();
                }
            }
            $response = [
                'success' => true,
                'data'    => null,
                'message' => 'The subject is unmarked as holiday',
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


        /* $classes = SmClass::get();
        $sections = SmClassSection::with('sectionName')
            ->where('class_id', $input['class'])
            ->get();
        $subjects = SmAssignSubject::with('subject')
            ->where('class_id', $input['class'])
            ->where('section_id', $input['section'])
            ->get(); */

        $students = StudentRecord::with('studentDetail', 'studentDetail.DateSubjectWiseAttendances')
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
            ->where('class_id', $input['class'])
            ->where('section_id', $input['section'])
            ->where('academic_id', SmAcademicYear::SINGLE_SCHOOL_API_ACADEMIC_YEAR())
            ->where('school_id', auth()->user()->school_id)
            ->get();

        if ($students->isEmpty()) {
            $response = [
                'success'  => false,
                'data' => null,
                'message' => 'No Result Found',
            ];
            return response()->json($response, 401);
        }

        $attendance_type = $students[0]['studentDetail']['DateSubjectWiseAttendances'] != null  ? $students[0]['studentDetail']['DateSubjectWiseAttendances']['attendance_type'] : '';
        $subject = SmSubject::withoutGlobalScopes([StatusAcademicSchoolScope::class])
            ->where('school_id', auth()->user()->school_id)
            ->select('id', 'subject_name')
            ->where('id', $request->subject_id)->first();
        $data['subject_name_id'] = $subject->id;
        $data['students'] = SubjectWiseSearchResource::collection($students);
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
                'message' => 'Subject wise student list'
            ];
        }
        return response()->json($response);
    }

    public function studentSubjectAttendanceSearch(Request $request)
    {
        if ($request->month || $request->year) {
            $this->validate($request, [
                'month' => "required",
                'year' => "required"
            ]);
        }
        $record = StudentRecord::find($request->record_id);
        if ($request->year && $request->month) {
            $year = $request->year;
            $month = sprintf('%02d', $request->month);
        } else {
            $year = date('Y');
            $month  = date('m');
        }
        $days = cal_days_in_month(CAL_GREGORIAN, $month, $year);
        $days2 = '';
        if ($month != 1) {
            $days2 = cal_days_in_month(CAL_GREGORIAN, $month - 1, $year);
        } else {
            $days2 = cal_days_in_month(CAL_GREGORIAN, $month, $year);
        }

        $current_day = date('d');

        $all_attendances = SmSubjectAttendance::whereNotNull('subject_id')
            ->where('school_id', auth()->user()->school_id)
            ->whereMonth('attendance_date', $month)
            ->whereYear('attendance_date', $year)
            ->where('student_record_id', $record->id)
            ->when($request->subject_id, function ($q) use ($request) {
                $q->where('subject_id', $request->subject_id);
            })
            ->select('attendance_type', 'attendance_date', 'subject_id')
            ->distinct('attendance_date')->get();

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

        (string)$data['class_name'] = SmClass::withoutGlobalScope(StatusAcademicSchoolScope::class)
            ->where('school_id', auth()->user()->school_id)
            ->where('id', $record->class_id)
            ->first()->class_name;
        (string)$data['section_name'] = SmSection::withoutGlobalScope(StatusAcademicSchoolScope::class)
            ->where('school_id', auth()->user()->school_id)
            ->where('id', $record->section_id)
            ->first()->section_name;
        (string)$data['subject_name'] = SmSubject::withoutGlobalScope(StatusAcademicSchoolScope::class)
            ->where('school_id', auth()->user()->school_id)
            ->where('id', $request->subject_id)
            ->first()->subject_name;


        $data['attendances'] = $attendances;
        $data['days'] = (int)$days;
        $data['total_present'] = (int)$total_pres;
        $data['total_absent'] = (int)$toal_abs;
        $data['total_late'] = (int)$total_late;
        $data['total_half_day'] = (int)$total_half;
        $data['total_holiday'] = (int)$total_holiday;
        $data['current_day'] = (string) $year . '-' . $month . '-' . $current_day;
        $data['status'] = (string)'Present: P, Late: L, Absent: A, Holiday: H, Half Day: F';

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
                'message' => 'Subject wise attendance report'
            ];
        }
        return response()->json($response);
    }
}
