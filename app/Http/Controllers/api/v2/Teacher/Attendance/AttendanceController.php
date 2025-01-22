<?php

namespace App\Http\Controllers\api\v2\Teacher\Attendance;

use Carbon\Carbon;
use App\SmAcademicYear;
use App\Scopes\SchoolScope;
use App\SmStudentAttendance;
use Illuminate\Http\Request;
use App\Models\StudentRecord;
use App\Scopes\AcademicSchoolScope;
use App\Scopes\GlobalAcademicScope;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Scopes\StatusAcademicSchoolScope;
use App\Http\Resources\v2\Admin\AttendanceStudentListResource;
use App\Http\Resources\v2\Teacher\Attendance\StudentAttendanceListResource;

class AttendanceController extends Controller
{
    public function studentAttendance(Request $request)
    {
        $students = StudentRecord::where('class_id', $request->class)
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
            ->where('section_id', $request->section)
            ->where('academic_id', SmAcademicYear::SINGLE_SCHOOL_API_ACADEMIC_YEAR())
            ->where('school_id', auth()->user()->school_id)->get();
        $data['students'] = StudentAttendanceListResource::collection($students);
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
                'message' => 'Attendance search result'
            ];
        }
        return response()->json($response);
    }

    public function attendanceReport(Request $request)
    {
        $record = StudentRecord::where('student_id',$request->student_attendance_id)->where('school_id', auth()->user()->school_id)->firstOrFail();
        if ($request->year && $request->month) {
            $year = $request->year;
            $month = sprintf('%02d', $request->month);
        } else {
            $year = date('Y');
            $month  = date('m');
        }

        $attendances = SmStudentAttendance::withoutGlobalScope(AcademicSchoolScope::class)
            ->where('student_id', $record->student_id)
            ->whereMonth('attendance_date', $month)
            ->whereYear('attendance_date', $year)
            ->where('academic_id', SmAcademicYear::SINGLE_SCHOOL_API_ACADEMIC_YEAR())->where('school_id', auth()->user()->school_id)
            ->where('student_record_id', $record->id)
            ->where('school_id', auth()->user()->school_id)
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
}
