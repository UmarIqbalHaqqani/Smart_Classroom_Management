<?php

namespace App\Http\Controllers\Admin\Examination;

use App\SmExam;
use App\SmClass;
use App\SmStaff;
use App\SmSection;
use App\SmStudent;
use App\SmSubject;
use App\YearCheck;
use App\SmExamType;
use App\SmExamSchedule;
use App\SmAssignSubject;
use App\SmExamAttendance;
use Illuminate\Http\Request;
use App\Models\StudentRecord;
use App\SmExamAttendanceChild;
use App\Traits\NotificationSend;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\App;
use App\Http\Controllers\Controller;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Auth;
use Modules\University\Entities\UnFaculty;
use Modules\University\Entities\UnSession;
use Modules\University\Entities\UnSubject;
use Modules\University\Entities\UnSemester;
use Modules\University\Entities\UnDepartment;
use Modules\University\Entities\UnAcademicYear;
use Modules\University\Entities\UnSemesterLabel;
use App\Http\Requests\Admin\Examination\SmExamAttendanceSearchRequest;
use Modules\University\Repositories\Interfaces\UnCommonRepositoryInterface;

class SmExamAttendanceController extends Controller
{
    use NotificationSend;
    public function __construct()
    {
        $this->middleware('PM');
    }

    public function examAttendanceCreate()
    {
        try {
            $exams = SmExamType::get();

            if (teacherAccess()) {
                $teacher_info = SmStaff::where('user_id',  Auth::user()->id)->first();
                $classes = $teacher_info->classes;
            } else {
                $classes = SmClass::get();
            }
            $subjects = SmSubject::get();
            return view('backEnd.examination.exam_attendance_create', compact('exams', 'classes', 'subjects'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function examAttendanceSearch(SmExamAttendanceSearchRequest $request)
    {
        try {
            if (moduleStatusCheck('University')) {
                $un_session = UnSession::find($request->un_session_id);
                $un_faculty = UnFaculty::find($request->un_faculty_id);
                $un_department = UnDepartment::find($request->un_department_id);
                $un_academic = UnAcademicYear::find($request->un_academic_id);
                $un_semester = UnSemester::find($request->un_semester_id);
                $un_semester_label = UnSemesterLabel::find($request->un_semester_label_id);
                $un_section = SmSection::find($request->un_section_id);

                $SmExamSchedule = SmExamSchedule::query();
                $exam_schedules = universityFilter($SmExamSchedule, $request)
                    ->where('exam_term_id', $request->exam_type)
                    ->where('un_subject_id', $request->subject_id)
                    ->orWhereNull('un_section_id')
                    ->count();

                if ($exam_schedules == 0 && !isSkip('exam_schedule')) {
                    Toastr::error('You have to create exam schedule first', 'Failed');
                    return redirect('exam-attendance-create');
                }

                $studentRecord = StudentRecord::query();
                $students = universityFilter($studentRecord, $request)
                    ->whereHas('studentDetail', function ($q) {
                        $q->where('active_status', 1);
                    })
                    ->get();

                if ($students->count() == 0) {
                    Toastr::error('No Student Record Found', 'Failed');
                    return redirect('exam-attendance-create');
                }

                $exams = SmExam::query();
                $exam_details = universityFilter($exams, $request)
                    ->where('active_status', 1)
                    ->where('exam_type_id', $request->exam_type)
                    ->first();

                $SmExamAttendance = SmExamAttendance::query();
                $exam_attendance = universityFilter($SmExamAttendance, $request)
                    ->where('un_subject_id', $request->subject_id)
                    ->where('exam_id', $exam_details->id)
                    ->first();

                $exam_attendance_childs = $exam_attendance != "" ? $exam_attendance->examAttendanceChild : [];
                $new_students = null;
                $exam_attendance =  $exam_attendance->where('exam_id', $request->exam)->first();
                $exam_attendance_childs = $exam_attendance != "" ? $exam_attendance->examAttendanceChild : [];
                if ($exam_attendance_childs) {
                    $already_submitted =  $exam_attendance_childs->pluck('student_record_id')->toArray();
                    $new_students = $students->whereNotIn('id', $already_submitted);
                }


                $subject_id = $request->subject_id;
                $exam_id  = $request->exam_type;
                $subjectName = UnSubject::find($subject_id);


                $data['un_semester_label_id'] = $request->un_semester_label_id;
                $interface = App::make(UnCommonRepositoryInterface::class);
                $data = $interface->oldValueSelected($request);

                return view('backEnd.examination.exam_attendance_create', compact(
                    'students',
                    'exam_attendance_childs',
                    'subject_id',

                    'exam_id',
                    'un_session',
                    'un_faculty',
                    'un_department',
                    'un_academic',
                    'un_semester',
                    'un_semester_label',
                    'un_section',
                    'subjectName',
                ))->with($data);
            } else {

                $exam_schedules = SmExamSchedule::where('subject_id', $request->subject)
                    ->when($request->class, function ($q) use ($request) {
                        $q->where('class_id', $request->class);
                    })
                    ->when($request->section, function ($q) use ($request) {
                        $q->where('section_id', $request->section);
                    })
                    ->where('exam_term_id', $request->exam)
                    ->count();

                if ($exam_schedules == 0 && !isSkip('exam_schedule')) {
                    Toastr::error('You have to create exam schedule first', 'Failed');
                    return redirect('exam-attendance-create');
                }

                $students = StudentRecord::with('class', 'section')
                    ->when($request->class, function ($q) use ($request) {
                        $q->where('class_id', $request->class);
                    })
                    ->when($request->section, function ($q) use ($request) {
                        $q->where('section_id', $request->section);
                    })
                    ->whereHas('studentDetail', function ($q) {
                        $q->where('active_status', 1);
                    })
                    ->where('school_id', auth()->user()->school_id)
                    ->where('academic_id', getAcademicId())
                    ->where('is_promote', 0)
                    ->get()->sortBy('roll_no');

                if ($students->count() == 0) {
                    Toastr::error('No Student Record Found', 'Failed');
                    return redirect('exam-attendance-create');
                }

                $exam = SmExam::where('exam_type_id', $request->exam)
                    ->where('class_id', $request->class)
                    ->where('section_id', $request->section)
                    ->where('subject_id', $request->subject)
                    ->first();

                $exam_attendance = SmExamAttendance::where('exam_id', $exam->id)
                    ->when($request->class, function ($q) use ($request) {
                        $q->where('class_id', $request->class);
                    })
                    ->when($request->section, function ($q) use ($request) {
                        $q->where('section_id', $request->section);
                    })
                    ->when($request->subject, function ($q) use ($request) {
                        $q->where('subject_id', $request->subject);
                    })
                    ->first();

                $exam_attendance_childs = $exam_attendance != "" ? $exam_attendance->examAttendanceChild : [];

                if (teacherAccess()) {
                    $teacher_info = SmStaff::where('user_id', Auth::user()->id)->first();
                    $classes = $teacher_info->classes;
                } else {
                    $classes = SmClass::get();
                }

                $exams    = SmExamType::get();
                $subjects = SmSubject::get();
                $exam_id  = $request->exam;
                $subject_id = $request->subject;
                $class_id = $request->class;
                $section_id = $request->section != null ? $request->section : null;

                $subject_info = SmSubject::find($request->subject);
                $search_info['class_name'] = SmClass::find($request->class)->class_name;
                $search_info['section_name'] =  $section_id == null ? 'All Sections' : SmSection::find($request->section)->section_name;
                $search_info['subject_name'] =  SmSubject::find($request->subject)->subject_name;

                return view('backEnd.examination.exam_attendance_create', compact('exams', 'classes', 'subjects', 'students', 'exam_id', 'subject_id', 'class_id', 'section_id', 'exam_attendance_childs', 'search_info'));
            }
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function examAttendanceStore(Request $request)
    {
        try {
            if (moduleStatusCheck('University')) {

                $SmExam = SmExam::query();
                $sm_exam = universityFilter($SmExam, $request)
                    ->where('exam_type_id', $request->exam_id)
                    ->where('un_subject_id', $request->un_subject_id)
                    ->first();

                $SmExamAttendance  = SmExamAttendance::query();
                $alreday_assigned = universityFilter($SmExamAttendance, $request)
                    ->where('un_subject_id', $request->un_subject_id)
                    ->where('exam_id', $sm_exam->id)
                    ->first();

                if ($alreday_assigned == "") {
                    $exam_attendance = new SmExamAttendance();
                } else {
                    $exam_attendance = universityFilter($SmExamAttendance, $request)
                        ->where('un_subject_id', $request->un_subject_id)
                        ->where('exam_id', $sm_exam->id)
                        ->first();
                }

                $common = App::make(UnCommonRepositoryInterface::class);
                $common->storeUniversityData($exam_attendance, $request);

                $exam_attendance->exam_id = $sm_exam->id;
                $exam_attendance->un_subject_id = $request->un_subject_id;
                $exam_attendance->created_at = YearCheck::getYear() . '-' . date('m-d h:i:s');
                $exam_attendance->school_id = Auth::user()->school_id;
                $exam_attendance->un_academic_id = getAcademicId();

                $exam_attendance->save();
                $exam_attendance->toArray();

                if ($alreday_assigned != "") {
                    SmExamAttendanceChild::where('exam_attendance_id', $exam_attendance->id)->delete();
                }

                foreach ($request->attendance as $record_id => $record) {
                    $exam_attendance_child = new SmExamAttendanceChild();
                    $exam_attendance_child->exam_attendance_id = $exam_attendance->id;
                    $exam_attendance_child->student_id = gv($record, 'student');
                    $exam_attendance_child->student_record_id = $record_id;
                    $exam_attendance_child->attendance_type = gv($record, 'attendance_type');
                    $exam_attendance_child->created_at = YearCheck::getYear() . '-' . date('m-d h:i:s');
                    $exam_attendance_child->school_id = Auth::user()->school_id;
                    $exam_attendance_child->un_academic_id = getAcademicId();
                    $exam_attendance_child->save();
                }
            } else {
                $exam = SmExam::where('exam_type_id', $request->exam_id)
                    ->where('class_id', $request->class_id)
                    ->where('section_id', $request->section_id)
                    ->where('subject_id', $request->subject_id)
                    ->first();
                if (is_null($exam)) {
                    Toastr::warning('Incomplete Exam setup', 'Failed');
                    return redirect()->back();
                }

                $alreday_assigned  = SmExamAttendance::where('exam_id', $exam->id)
                    ->when($request->class_id, function ($q) use ($request) {
                        $q->where('class_id', $request->class_id);
                    })
                    ->when($request->section_id, function ($q) use ($request) {
                        $q->where('section_id', $request->section_id);
                    })
                    ->when($request->subject_id, function ($q) use ($request) {
                        $q->where('subject_id', $request->subject_id);
                    })
                    ->first();

                DB::beginTransaction();
                // DB::statement('SET FOREIGN_KEY_CHECKS=0;');

                if ($request->section_id != '') {
                    if ($alreday_assigned == "") {
                        $exam_attendance = new SmExamAttendance();
                    } else {
                        $exam_attendance = SmExamAttendance::where('class_id', $request->class_id)
                            ->where('section_id', $request->section_id)
                            ->where('subject_id', $request->subject_id)
                            ->where('exam_id', $exam->id)
                            ->first();
                    }
                    $this->storeAttendance($exam_attendance, $request, $request->section_id, $alreday_assigned);

                    $data['class'] = $exam_attendance->class->class_name;
                    $data['section'] = $exam_attendance->section->section_name;
                    $data['subject'] = $exam_attendance->subject->subject_name;
                    $records = $this->studentRecordInfo($request->class_id, $request->section_id)->pluck('studentDetail.user_id');
                    $this->sent_notifications('Exam_Attendance', $records, $data, ['Student', 'Parent']);
                } else {
                    $classSections = SmAssignSubject::where('class_id', $request->class_id)
                        ->where('subject_id', $request->subject_id)
                        ->distinct(['section_id', 'subject_id'])
                        ->get();
                    foreach ($classSections as $section) {
                        $exam_attendance = SmExamAttendance::where('class_id', $request->class_id)
                            ->where('section_id', $section->section_id)
                            ->where('subject_id', $request->subject_id)
                            ->where('exam_id', $exam->id)
                            ->first();
                        if (!$exam_attendance) {
                            $exam_attendance = new SmExamAttendance();
                        };
                        $this->storeAttendance($exam_attendance, $request, $section->section_id, $alreday_assigned);

                        $data['class'] = $exam_attendance->class->class_name;
                        $data['section'] = $exam_attendance->section->section_name;
                        $data['subject'] = $exam_attendance->subject->subject_name;
                        $records = $this->studentRecordInfo($request->class_id, $section->section_id)->pluck('studentDetail.user_id');
                        $this->sent_notifications('Exam_Attendance', $records, $data, ['Student', 'Parent']);
                    }
                }

                DB::commit();
            }

            Toastr::success('Operation successful', 'Success');
            return redirect('exam-attendance-create');
        } catch (\Exception $e) {
            DB::rollback();
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }
    private function storeAttendance($exam_attendance, $request, int $section_id, $alreday_assigned = null)
    {
        $exam = SmExam::where('exam_type_id', $request->exam_id)
            ->where('class_id', $request->class_id)
            ->where('section_id', $request->section_id)
            ->where('subject_id', $request->subject_id)
            ->first();

        $exam_attendance->exam_id = $exam->id;
        $exam_attendance->subject_id = $request->subject_id;
        $exam_attendance->class_id = $request->class_id;
        $exam_attendance->section_id = $section_id;
        $exam_attendance->created_at = YearCheck::getYear() . '-' . date('m-d h:i:s');
        $exam_attendance->school_id = Auth::user()->school_id;
        $exam_attendance->academic_id = getAcademicId();
        $exam_attendance->save();
        $exam_attendance->toArray();

        if ($alreday_assigned != "") {
            SmExamAttendanceChild::where('exam_attendance_id', $exam_attendance->id)->delete();
        }

        foreach ($request->attendance as $record_id => $record) {
            $exam_attendance_child = new SmExamAttendanceChild();
            $exam_attendance_child->exam_attendance_id = $exam_attendance->id;

            $exam_attendance_child->student_id = gv($record, 'student');
            $exam_attendance_child->student_record_id = $record_id;
            $exam_attendance_child->class_id = gv($record, 'class');
            $exam_attendance_child->section_id = gv($record, 'section');
            $exam_attendance_child->attendance_type = gv($record, 'attendance_type');

            $exam_attendance_child->created_at = YearCheck::getYear() . '-' . date('m-d h:i:s');
            $exam_attendance_child->school_id = Auth::user()->school_id;
            $exam_attendance_child->academic_id = getAcademicId();
            $exam_attendance_child->save();
        }
    }
}
