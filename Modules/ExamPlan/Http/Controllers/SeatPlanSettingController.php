<?php

namespace Modules\ExamPlan\Http\Controllers;

use App\SmClass;
use App\SmStudent;
use App\SmExamType;
use App\SmSeatPlan;
use App\SmExamSchedule;
use Illuminate\Http\Request;
use App\Models\StudentRecord;
use App\Traits\NotificationSend;
use Illuminate\Routing\Controller;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Auth;
use Modules\ExamPlan\Entities\SeatPlan;
use Illuminate\Support\Facades\Validator;
use Illuminate\Contracts\Support\Renderable;
use Modules\ExamPlan\Entities\SeatPlanSetting;

class SeatPlanSettingController extends Controller
{
    use NotificationSend;
    public function setting()
    {
        try {
            $setting = SeatPlanSetting::where('school_id', Auth::user()->school_id)->where('academic_id', getAcademicId())->first();
            if (!$setting) {
                $oldSetting = SeatPlanSetting::where('school_id', Auth::user()->school_id)->latest()->first();
                $setting = $oldSetting->replicate();
                $setting->academic_id = getAcademicId();
                $setting->save();
            }
            return view('examplan::setting.seatplanSetting', compact('setting'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Error');
            return redirect()->back();
        }
    }

    public function settingUpdate(Request $request)
    {

        try {
            $setting = SeatPlanSetting::where('school_id', Auth::user()->school_id)->where('academic_id', getAcademicId())->first();
            if (!$setting) {
                $oldSetting = SeatPlanSetting::where('school_id', Auth::user()->school_id)->latest()->first();
                $setting = $oldSetting->replicate();
                $setting->academic_id = getAcademicId();
            }
            $setting->school_name = $request->school_name;
            $setting->student_photo = $request->student_photo;
            $setting->student_name = $request->student_name;
            $setting->roll_no = $request->roll_no;
            $setting->admission_no = $request->admission_no;
            $setting->class_section = $request->class_section;
            $setting->exam_name = $request->exam_name;
            $setting->academic_year = $request->academic_year;
            $setting->save();
            Toastr::success('Update Successfully', 'success');
            return redirect()->back();
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Error');
            return redirect()->back();
        }
    }


    public function seatplan()
    {
        try {
            $exams = SmExamType::where('active_status', 1)
                ->where('academic_id', getAcademicId())
                ->where('school_id', Auth::user()->school_id)
                ->get();
            $classes = SmClass::where('academic_id', getAcademicId())->where('school_id', auth()->user()->school_id)->get();
            return view('examplan::seatPlan', compact('exams', 'classes'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Error');
            return redirect()->back();
        }
    }

    public function index()
    {
        return view('examplan::create');
    }
    function universitySeatPlanSearch($request)
    {

        $input = $request->all();
        $validator = Validator::make($input, [
            'un_session_id' => 'required',
            'un_faculty_id' => 'required',
            'un_department_id' => 'required',
            'un_academic_id' => 'required',
            'un_semester_id' => 'required',
            'un_semester_label_id' => 'required',
            'un_section_id' => 'required',
            'exam_type' => 'required',
        ], [
            'un_session_id.required' => 'The session field is required',
            'un_faculty_id.required' => 'The faculty field is required',
            'un_department_id.required' => 'The department field is required',
            'un_academic_id.required' => 'The academic field is required',
            'un_semester_id.required' => 'The semester field is required',
            'un_semester_label_id.required' => 'The semester label field is required',
            'un_section_id.required' => 'The section field is required',
            'exam_type.required' => 'The exam type field is required',
        ]);
        if ($validator->fails()) {
            return redirect()->route('examplan.seatplan.index')
                ->withErrors($validator)
                ->withInput();
        }
        try {

            $exam = SmExamSchedule::query();
            $exam_id = $request->exam_type;
            // $class_id = $request->class;
            $exam->where('school_id', auth()->user()->school_id)->where('un_academic_id', getAcademicId());
            if ($request->exam != "") {
                $exam->where('exam_term_id', $request->exam_type);
            }
            if ($request->un_semester_label_id != "") {
                $exam->where('un_semester_label_id', $request->un_semester_label_id);
            }
            if ($request->un_section_id != "") {
                $exam->where('un_section_id', $request->un_section_id);
            }
            $exam_routine = $exam->get();
            if ($exam_routine) {

                $seat_plans = SeatPlan::where('exam_type_id', $request->exam_type)
                    ->where('school_id', Auth::user()->school_id)
                    ->where('un_academic_id', getAcademicId())
                    ->get(['student_record_id']);

                $seat_plan_ids = [];
                foreach ($seat_plans as $seatPlan) {
                    $seat_plan_ids[] =  $seatPlan->student_record_id;
                }
                $student_records = StudentRecord::query();

                $student_records->where('school_id', auth()->user()->school_id)
                    ->where('un_academic_id', getAcademicId())
                    ->where('is_promote', 0)
                    ->whereHas('student', function ($q) {
                        $q->where('active_status', 1);
                    });
                if ($request->un_semester_label_id != "") {
                    $student_records->where('un_semester_label_id', $request->un_semester_label_id);
                }
                if ($request->un_section_id != "") {
                    $student_records->where('un_section_id', $request->un_section_id);
                }

                $records = $student_records->get();
                return view('examplan::seatPlan', compact('records', 'seat_plan_ids', 'exam_id'));
            } else {
                Toastr::warning('Exam shedule is not ready', 'warning');
                return redirect()->back();
            }
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Error');
            return redirect()->back();
        }
    }

    public function seatplanSearch(Request $request)
    {
        if (moduleStatusCheck('University')) {
            return $this->universitySeatPlanSearch($request);
        } else {

            try {
                $input = $request->all();
                $validator = Validator::make($input, [
                    'exam' => 'required',
                    'class' => 'required',
                    'section' => 'required',
                ]);
                if ($validator->fails()) {
                    return redirect()->route('examplan.seatplan.index')
                        ->withErrors($validator)
                        ->withInput();
                }

                $exam = SmExamSchedule::query();
                $exam_id = $request->exam;
                $class_id = $request->class;
                $exam->where('school_id', auth()->user()->school_id)->where('academic_id', getAcademicId());
                if ($request->exam != "") {
                    $exam->where('exam_term_id', $request->exam);
                }
                if ($request->class != "") {
                    $exam->where('class_id', $request->class);
                }
                if ($request->section != "") {
                    $exam->where('section_id', $request->section);
                }
                $exam_routine = $exam->get();
                if ($exam_routine) {

                    $seat_plans = SeatPlan::where('exam_type_id', $request->exam)
                        ->where('school_id', Auth::user()->school_id)
                        ->where('academic_id', getAcademicId())
                        ->get(['student_record_id']);

                    $seat_plan_ids = [];
                    foreach ($seat_plans as $seatPlan) {
                        $seat_plan_ids[] =  $seatPlan->student_record_id;
                    }
                    $student_records = StudentRecord::query();

                    $student_records->where('school_id', auth()->user()->school_id)
                        ->where('academic_id', getAcademicId())
                        ->where('is_promote', 0)
                        ->whereHas('student', function ($q) {
                            $q->where('active_status', 1);
                        });
                    if ($request->class != "") {
                        $student_records->where('class_id', $request->class);
                    }
                    if ($request->section != "") {
                        $student_records->where('section_id', $request->section);
                    }

                    $records = $student_records->get();

                    $exams = SmExamType::where('active_status', 1)
                        ->where('academic_id', getAcademicId())
                        ->where('school_id', Auth::user()->school_id)
                        ->get();
                    $classes = SmClass::where('academic_id', getAcademicId())
                        ->where('school_id', auth()->user()->school_id)
                        ->get();
                    return view('examplan::seatPlan', compact('exams', 'classes', 'records', 'exam_id', 'class_id', 'seat_plan_ids'));
                } else {
                    Toastr::warning('Exam shedule is not ready', 'warning');
                    return redirect()->back();
                }
            } catch (\Exception $e) {
                Toastr::error('Operation Failed', 'Error');
                return redirect()->back();
            }
        }
    }

    public function show($id)
    {
        return view('examplan::show');
    }

    function universitySeatPlanGenerate($request)
    {
        try {
            $student_records = [];
            $studentRecord = null;
            if ($request->data) {
                foreach ($request->data as $key => $data) {
                    if (count($data) == 2) {
                        $student_records[] = $data['student_record_id'];
                    }
                }
                foreach ($student_records as $record) {
                    $seat_plan = SeatPlan::where('student_record_id', $record)->where('exam_type_id', $request->exam_type_id)->first();
                    $studentRecord = StudentRecord::find($record);
                    if (!$seat_plan) {
                        $new_seat = new SeatPlan();
                        $new_seat->student_record_id = $record;
                        $new_seat->exam_type_id = $request->exam_type_id;
                        $new_seat->created_by = Auth::id();
                        $new_seat->school_id = Auth::user()->school_id;
                        $new_seat->un_academic_id = getAcademicId();
                        $new_seat->save();

                        $data['un_semester_label_id'] = $new_seat->studentRecord->un_semester_label_id;
                        $data['un_section_id'] = $new_seat->studentRecord->un_section_id;
                        $records = $this->unStudentRecordInfo($data['un_semester_label_id'], $data['un_section_id'])->pluck('studentDetail.user_id');
                        $this->sent_notifications('Exam_Seat_Plan', $records, $data, ['Student', 'Parent']);

                        $student_id = StudentRecord::find($record)->student_id;
                        $student = SmStudent::find($student_id);
                        $exam_type = SmExamType::find($request->exam_type_id);
                    }
                }
                $seat_plans = SeatPlan::with('studentRecord.studentDetail')->where('exam_type_id', $request->exam_type_id)->where('school_id', Auth::user()->school_id)->where('un_academic_id', getAcademicId())->whereIn('student_record_id', $student_records)->get();

                $setting = SeatPlanSetting::where('school_id', Auth::user()->school_id)->where('un_academic_id', getAcademicId())->first();
                if (!$setting) {
                    $oldSetting = SeatPlanSetting::where('school_id', Auth::user()->school_id)->latest()->first();
                    $setting = $oldSetting->replicate();
                    $setting->un_academic_id = getAcademicId();
                    $setting->save();
                }

                return view('examplan::seatplanPrint', compact('setting', 'seat_plans'));
            }
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Error');
            return redirect()->back();
        }
    }

    public function seatplanGenerate(Request $request)
    {
        if (moduleStatusCheck('University')) {
            return $this->universitySeatPlanGenerate($request);
        } else {

            try {
                $student_records = [];
                $studentRecord = null;
                if ($request->data) {
                    foreach ($request->data as $key => $data) {
                        if (count($data) == 2) {
                            $student_records[] = $data['student_record_id'];
                        }
                    }
                    $unique_user_ids = [];  

                    foreach ($student_records as $record) {
                        $seat_plan = SeatPlan::where('student_record_id', $record)
                                            ->where('exam_type_id', $request->exam_type_id)
                                            ->first();
                    
                        if (!$seat_plan) {
                            $new_seat = new SeatPlan();
                            $new_seat->student_record_id = $record;
                            $new_seat->exam_type_id = $request->exam_type_id;
                            $new_seat->created_by = Auth::id();
                            $new_seat->school_id = Auth::user()->school_id;
                            $new_seat->academic_id = getAcademicId();
                            $new_seat->save();
                    
                            $data['class_id'] = $new_seat->studentRecord->class_id;
                            $data['section_id'] = $new_seat->studentRecord->section_id;
                    
                            $user_id = StudentRecord::find($record)->studentDetail->user_id;
                    
                            if (!in_array($user_id, $unique_user_ids)) {
                                $unique_user_ids[] = $user_id;
                            }
                        }
                    }
                    
                    foreach ($unique_user_ids as $user_id) {
                        $this->sent_notifications('Exam_Seat_Plan', [$user_id], $data, ['Student', 'Parent']);
                    }
                
                    $seat_plans = SeatPlan::with('studentRecord.studentDetail')->where('exam_type_id', $request->exam_type_id)->where('school_id', Auth::user()->school_id)->where('academic_id', getAcademicId())->whereIn('student_record_id', $student_records)->get();

                    $setting = SeatPlanSetting::where('school_id', Auth::user()->school_id)->where('academic_id', getAcademicId())->first();
                    if (!$setting) {
                        $oldSetting = SeatPlanSetting::where('school_id', Auth::user()->school_id)->latest()->first();
                        $setting = $oldSetting->replicate();
                        $setting->academic_id = getAcademicId();
                        $setting->save();
                    }

                    return view('examplan::seatplanPrint', compact('setting', 'seat_plans'));
                }
            } catch (\Exception $e) {
                Toastr::error('Operation Failed', 'Error');
                return redirect()->back();
            }
        }
    }
}
