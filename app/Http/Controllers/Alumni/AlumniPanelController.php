<?php

namespace App\Http\Controllers\Alumni;

use App\Http\Controllers\SmAcademicCalendarController;
use App\Http\Controllers\Controller;
use App\Models\SmCalendarSetting;
use App\Models\StudentRecord;
use App\Models\User;
use App\SmAssignSubject;
use App\SmBookIssue;
use App\SmComplaint;
use App\SmEvent;
use App\SmExamSchedule;
use App\SmHoliday;
use App\SmHomework;
use App\SmLeaveDefine;
use App\SmMarksGrade;
use App\SmNoticeBoard;
use App\SmOnlineExam;
use App\SmStudent;
use App\SmStudentAttendance;
use App\SmStudentDocument;
use App\SmStudentTimeline;
use App\SmSubjectAttendance;
use App\SmVehicle;
use App\SmWeekend;
use Brian2694\Toastr\Facades\Toastr;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Modules\OnlineExam\Entities\InfixOnlineExam;
use Modules\RolePermission\Entities\InfixRole;

class AlumniPanelController extends Controller 

{
    public function alumniDashboard () {
        if (moduleStatusCheck('Alumni')) {
            $user = auth()->user();
            $role_id    = InfixRole::where('is_saas',0)->where('name', 'Alumni')->first()->id;
            if ($user) {
                $user_id = $user->id;
            }

            $student_detail = auth()->user()->student->load('studentRecords', 'feesAssign', 'feesAssignDiscount');

            $data['documents']      = SmStudentDocument::where('student_staff_id', $student_detail->id)
                                ->where('type', 'stu')
                                ->where('academic_id', getAcademicId())
                                ->where('school_id', $user->school_id)
                                ->get();

            $data['timelines']      = SmStudentTimeline::where('staff_student_id', $student_detail->id)
                                ->where('type', 'stu')
                                ->where('visible_to_student', 1)
                                ->where('academic_id', getAcademicId())
                                ->where('school_id', $user->school_id)
                                ->get();

            $data['totalNotices']   = SmNoticeBoard::where('publish_on', '<=', date('Y-m-d'))->where('active_status', 1)->where('academic_id', getAcademicId())->where('school_id', auth()->user()->school_id)
                                ->where(function ($query) {
                                    $query->whereJsonContains('inform_to', '10')
                                        ->orWhere('inform_to', '10');
                                    })
                                    ->get();

            $data['issueBooks'] = SmBookIssue::where('member_id', $student_detail->user_id)
                ->where('issue_status', 'I')
                ->where('academic_id', getAcademicId())
                ->where('school_id', $user->school_id)
                ->get();

            $data['smEvents'] = SmEvent::where('active_status', 1)
                ->where('academic_id', getAcademicId())
                ->where('from_date', '>=', date('Y-m-d'))
                ->where('school_id', $user->school_id)
                ->where(function( $quest) {
                    $quest->whereJsonContains('role_ids','10')
                        ->orWhere('role_ids','10');
                })
                ->get();

            $data['student_detail'] = SmStudent::where('user_id', $user->id)->first();

            $data['sm_weekends']    = SmWeekend::orderBy('order', 'ASC')
                                ->where('active_status', 1)
                                ->where('is_weekend', 1)
                                ->where('school_id', $user->school_id)
                                ->get();

            if (moduleStatusCheck('University')) {
                $records = StudentRecord::where('student_id', $student_detail->id)
                    ->where('un_academic_id', getAcademicId())->get();
            } else {
                $records = StudentRecord::where('student_id', $student_detail->id)
                    ->where('academic_id', getAcademicId())->get();
            }
            
            $data['student_details']  = Auth::user()->student->load('studentRecords', 'attendances');
            $data['student_records']  = $data['student_details']->studentRecords;
            
            $data['settings'] = SmCalendarSetting::get();
            $data['roles'] = InfixRole::where('is_saas',0)->where(function ($q) {
                $q->where('school_id', auth()->user()->school_id)->orWhere('type', 'System');
                })
                ->whereNotIn('id', [1, 2])
                ->get();
                
            $academicCalendar = new SmAcademicCalendarController();
            $data['events'] = $academicCalendar->calenderData();
        } else {
            abort(404);
        }        
        return view('backEnd.alumniPanel.alumni_dashboard', $data);
    }

    public function viewEvent($id)
    {
        try {
            $event = SmEvent::find($id);
            return view('alumni::inc._view_event', compact('event'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }
    public function viewDocument($id)
    {
        try {
            $document = SmStudentDocument::find($id);
            return view('alumni::inc._view_document', compact('document'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function studentProfile()
    {
        try {
            $student_id = Auth::user()->student->id;
            $student_detail = SmStudent::find($student_id);
            return view('backEnd.alumniPanel.inc._student_profile', compact('student_detail'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }
}