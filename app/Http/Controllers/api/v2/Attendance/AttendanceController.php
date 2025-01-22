<?php

namespace App\Http\Controllers\api\v2\Attendance;

use App\SmStudent;
use Carbon\Carbon;
use App\SmAssignSubject;
use App\Scopes\SchoolScope;
use App\SmStudentAttendance;
use App\SmSubjectAttendance;
use Illuminate\Http\Request;
use App\Models\StudentRecord;
use App\Http\Controllers\Controller;
use App\Scopes\AcademicSchoolScope;
use App\Scopes\StatusAcademicSchoolScope;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AttendanceController extends Controller
{
    public function stdAttendCurrMonth(Request $request)
    {
        $this->validate($request, [
            'month' => 'nullable|required_with:year|date_format:m',
            'year'  => 'nullable|required_with:month|date_format:Y'
        ], [
            'month.date_format' => 'Invalid month.',
            'year.date_format' => 'Invalid year.',
        ]);

        $student = SmStudent::withoutGlobalScopes([SchoolScope::class])
            ->where('school_id', auth()->user()->school_id)
            ->where('id', $request->student_id)
            ->firstOrFail();

        $student_detail = $student->load('studentRecords', 'attendances');

        if ($request->year && $request->month) {
            $year = $request->year;
            $month = sprintf('%02d', $request->month);
        } else {
            $year = date('Y');
            $month  = date('m');
        }
        $current_day = date('d');

        $attendances = SmStudentAttendance::withoutGlobalScopes([AcademicSchoolScope::class])
            ->where('student_id', $student_detail->id)
            ->whereYear('attendance_date', $year)
            ->whereMonth('attendance_date', $month)
            ->where('student_record_id', $request->record_id)
            ->where('school_id', auth()->user()->school_id)
            ->select('attendance_type', 'attendance_date')
            ->get();

        $data['attendances']    = $attendances;
        $data['current_day']    = (string)$year . '-' . $month . '-' . $current_day;
        $data['status']         = (string)'Present: P, Late: L, Absent: A, Holiday: H, Half Day: F';

        $data['P'] = (int)$attendances->where('attendance_type', 'P')->count();
        $data['L'] = (int)$attendances->where('attendance_type', 'L')->count();
        $data['A'] = (int)$attendances->where('attendance_type', 'A')->count();
        $data['H'] = (int)$attendances->where('attendance_type', 'H')->count();
        $data['F'] = (int)$attendances->where('attendance_type', 'F')->count();


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
                'message' => "$month-$year attendance"
            ];
        }
        return response()->json($response);
    }

    public function stdAttendSubjectWise(Request $request)
    {
        $this->validate($request, [
            'month' => 'nullable|required_with:year|date_format:m',
            'year'  => 'nullable|required_with:month|date_format:Y'
        ], [
            'month.date_format' => 'Invalid month.',
            'year.date_format'  => 'Invalid year.',
        ]);

        if ($request->year && $request->month) {
            $year = $request->year;
            $month = sprintf('%02d', $request->month);
        } else {
            $year = date('Y');
            $month  = date('m');
        }

        $current_day = date('d');

        $subject_id = $request->subject_id;

        $student = SmStudent::findOrFail($request->student_id);
        $student_detail = $student->load('studentRecords', 'attendances');

        $attendances = SmSubjectAttendance::withoutGlobalScopes([AcademicSchoolScope::class])
            ->where('student_id', $student_detail->id)
            ->whereYear('attendance_date', $year)
            ->whereMonth('attendance_date', $month)
            ->when($subject_id, function ($query) use ($subject_id) {
                $query->where('subject_id', $subject_id);
            })
            ->where('student_record_id', $request->record_id)
            ->where('school_id', auth()->user()->school_id)
            ->select('attendance_type', 'attendance_date')
            ->get();
        $data['attendances'] = $attendances;

        $data['current_day'] = (string)$year . '-' . $month . '-' . $current_day;
        $data['status'] = (string)'Present: P, Late: L, Absent: A, Holiday: H, Half Day: F';

        $data['P'] = (int)$attendances->where('attendance_type', 'P')->count();
        $data['L'] = (int)$attendances->where('attendance_type', 'L')->count();
        $data['A'] = (int)$attendances->where('attendance_type', 'A')->count();
        $data['H'] = (int)$attendances->where('attendance_type', 'H')->count();
        $data['F'] = (int)$attendances->where('attendance_type', 'F')->count();

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
                'message' => "Subject attendance",
            ];
        }

        return response()->json($response);
    }

    public function recWiseAllSubjects(Request $request)
    {
        $request->validate([
            'record_id' => 'required'
        ]);

        $record = StudentRecord::where('school_id', auth()->user()->school_id)->where('id', $request->record_id)->firstOrFail();

        $assignSubjects = SmAssignSubject::withoutGlobalScopes([StatusAcademicSchoolScope::class])
            ->with(['subject' => function ($q1) {
                $q1->select('id', 'subject_name', 'subject_code', 'subject_type');
            }])
            ->where('class_id', $record->class_id)
            ->where('section_id', $record->section_id)
            ->where('academic_id', $record->academic_id)
            ->where('school_id', auth()->user()->school_id)
            ->select('active_status', 'subject_id')
            ->get();
        if (!$assignSubjects) {
            $response = [
                'success' => false,
                'data'    => null,
                'message' => 'Operation failed'
            ];
        } else {
            $response = [
                'success' => true,
                'data'    => $assignSubjects,
                'message' => 'Your all subject'
            ];
        }
        return response()->json($response);
    }
}
