<?php

namespace App\Http\Controllers;

use App\GlobalVariable;
use App\Role;
use App\SmBook;
use App\SmClass;
use App\SmEvent;
use App\SmStaff;
use App\SmHoliday;
use App\SmSection;
use App\SmSubject;
use Carbon\Carbon;
use App\SmExamType;
use App\SmHomework;
use App\Models\User;
use App\SmBookIssue;
use App\SmClassRoom;
use App\SmOnlineExam;
use App\SmNoticeBoard;
use App\SmExamSchedule;
use App\SmLeaveRequest;
use App\SmAdmissionQuery;
use Illuminate\Http\Request;
use App\SmTeacherUploadContent;
use App\Models\SmCalendarSetting;
use App\Http\Controllers\Controller;
use Brian2694\Toastr\Facades\Toastr;
use Modules\Lesson\Entities\LessonPlanner;
use Modules\RolePermission\Entities\InfixRole;

class SmAcademicCalendarController extends Controller
{
    public function academicCalendarView(){
        $data['settings'] = SmCalendarSetting::get();
        $data['roles'] = InfixRole::where('is_saas', 0)->where(function ($q) {
            $q->where('school_id', auth()->user()->school_id)->orWhere('type', 'System');
        })
        ->whereNotIn('id', [1])
        ->get();

        $data['events'] = $this->calenderData();
        return view('backEnd.communicate.academicCalendar', $data);
    }

    public function storeAcademicCalendarSettings(Request $request){
        try{
            foreach(gv($request, 'setting') as $key => $data){
                $settings = SmCalendarSetting::where('menu_name', $key)->first();
                $settings->status = gv($data, 'status');
                $settings->font_color = gv($data, 'font_color');
                $settings->bg_color = gv($data, 'bg_color');
                $settings->school_id = auth()->user()->school_id;
                $settings->update();
            }
            Toastr::success('Update Successfully', 'Success');
            return redirect()->route('academic-calendar');
        }catch(\Exception $e){
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

	public function getRoleNames($role_ids){
		return Role::whereIn('id', explode(',', $role_ids))->pluck('name');
	}

	public function getCalendarSettings($menu_name){
        return SmCalendarSetting::where('menu_name', $menu_name)
            ->select('font_color', 'bg_color', 'status')
            ->first();  
	}

    public function alumniGetCalendarSettings($menu_name){
        if(auth()->user()->role_id == GlobalVariable::isAlumni()){
            $data['status'] = 0;
            $obj = (object) $data;
            return $obj;
        }else{
            return SmCalendarSetting::where('menu_name', $menu_name)
            ->select('font_color', 'bg_color', 'status')
            ->first();
        }
    }

    public function calenderData(){
        // CalenderQuery Start
        try{
        $admissionQuries = [];
        $homeworks = [];
        $studyMaterials = [];
        $allEvents = [];
        $holidays = [];
        $examRounines = [];
        $noticeBoards = [];
        $onlineExams = [];
        $lessonPlans = [];
        $leaveDatas = [];
        $libraryDatas = [];

        $admissionQuerySettings = $this->alumniGetCalendarSettings('admission_query');
        $homeworkSettings = $this->alumniGetCalendarSettings('homework');
        $studyMaterialSettings = $this->alumniGetCalendarSettings('study_material');
        $eventSettings = $this->getCalendarSettings('event');
        $holidaySettings = $this->alumniGetCalendarSettings('holiday');
        $examSettings = $this->alumniGetCalendarSettings('exam');
        $noticeBoardSettings = $this->getCalendarSettings('notice_board');
        $onlineExamSettings = $this->alumniGetCalendarSettings('online_exam');
        $lessonPlanSettings = $this->alumniGetCalendarSettings('lesson_plan');
        $leaveSettings = $this->alumniGetCalendarSettings('leave');
        $librarySettings = $this->alumniGetCalendarSettings('library');

        $userRoleId = auth()->user()->role_id;
        $roleInfo = auth()->user()->roles;

        if($userRoleId == 2){
            $studentRecords = auth()->user()->student->studentRecords;
        }else{
            $studentRecords = [];
        }

        if($userRoleId == 3){
            $childrenRecords = [];
            $childrenUserids = [];
            $childrenInfos = auth()->user()->parent->childrens;
            foreach($childrenInfos as $child){
                $childrenRecords['class_id'] = $child->studentRecords->pluck('class_id')->toArray();
                $childrenRecords['section_id'] = $child->studentRecords->pluck('section_id')->toArray();
                $childrenUserids[] = $child->user_id;
            }
        }else{
            $childrenRecords = [];
            $childrenUserids = [];
        }

        if($userRoleId == 4){
            $teacherAccess = auth()->user()->staff->classes->pluck('id');
        }else{
            $teacherAccess = [];
        }

        if($admissionQuerySettings->status == 1){
            if($userRoleId != 2 && $userRoleId != 3){
                $admissionQuries = SmAdmissionQuery::get(['name', 'phone', 'email', 'address', 'next_follow_up_date']);
            }
        }

        if($homeworkSettings->status == 1){
            $homeworks = SmHomework::with('class', 'section', 'subjects')
            ->when($userRoleId == 2, function($s) use ($studentRecords){
                $class_ids = $studentRecords->pluck('class_id')->toArray();
                $section_ids = $studentRecords->pluck('section_id')->toArray();
                $s->whereIn('class_id', $class_ids);
                $s->orWhereIn('section_id', $section_ids);
            })
            ->when($userRoleId == 3, function($s) use ($childrenRecords){
                $s->whereIn('class_id', gv($childrenRecords,'class_id'));
                $s->orWhereIn('section_id', gv($childrenRecords,'section_id'));
            })
            ->when($userRoleId == 4, function ($t) use($teacherAccess){
                $t->whereIn('class_id', $teacherAccess);
                $t->orWhere('created_by', auth()->user()->staff->id);
            })
            ->get(['class_id', 'section_id', 'subject_id', 'description', 'submission_date']);
        }
        if($studyMaterialSettings->status == 1){
            $studyMaterials = SmTeacherUploadContent::with('classes', 'sections')
            ->when($userRoleId == 2, function($s) use ($studentRecords){
                $class_ids = $studentRecords->pluck('class_id')->toArray();
                $section_ids = $studentRecords->pluck('section_id')->toArray();
                $s->whereIn('class', $class_ids);
                $s->orWhereIn('section', $section_ids);
                $s->orWhere('available_for_all_classes', 1);
            })
            ->when($userRoleId == 3, function($s) use ($childrenRecords){
                $s->whereIn('class', gv($childrenRecords,'class_id'));
                $s->orWhereIn('section', gv($childrenRecords,'section_id'));
            })
            ->when($userRoleId != 2 && $userRoleId != 3, function($a){
                $a->where('available_for_admin', 1);
            })
            ->when($userRoleId == 4, function ($t) use($teacherAccess){
                $t->whereIn('class', $teacherAccess);
                $t->orWhere('created_by', auth()->user()->staff->id);
            })
            ->get(['content_title', 'content_type', 'description', 'upload_date', 'upload_file', 'class', 'section']);
        }

        if($eventSettings->status == 1){
            $allEvents = SmEvent::when($roleInfo->name != 'Super admin', function($a) use($roleInfo){
                $a->whereJsonContains('role_ids', (string)$roleInfo->id);
            })
            ->get(['event_title', 'event_location', 'event_des', 'from_date', 'to_date', 'uplad_image_file', 'url']);
        }

        if($holidaySettings->status == 1){
            $holidays = SmHoliday::where('active_status', 1)->get(['holiday_title', 'details', 'from_date', 'to_date', 'upload_image_file']);
        }

        if($examSettings->status == 1){
            $examRounines = SmExamSchedule::with('class', 'section', 'subject', 'examType', 'teacher', 'classRoom')
            ->when($userRoleId == 2, function($s) use ($studentRecords){
                $class_ids = $studentRecords->pluck('class_id')->toArray();
                $section_ids = $studentRecords->pluck('section_id')->toArray();
                $s->whereIn('class_id', $class_ids);
                $s->orWhereIn('section_id', $section_ids);
            })
            ->when($userRoleId == 3, function($s) use ($childrenRecords){
                $s->whereIn('class_id', gv($childrenRecords,'class_id'));
                $s->orWhereIn('section_id', gv($childrenRecords,'section_id'));
            })
            ->when($userRoleId == 4, function ($t) use($teacherAccess){
                $t->whereIn('class_id', $teacherAccess);
                $t->orWhere('created_by', auth()->user()->staff->id);
            })
            ->where('active_status', 1)
            ->get(['exam_term_id', 'subject_id', 'class_id', 'section_id', 'start_time', 'end_time', 'teacher_id', 'room_id', 'date']);
        }

        if($noticeBoardSettings->status == 1){
            $noticeBoards = SmNoticeBoard::where('publish_on', '<=', date('Y-m-d'))->when($roleInfo->name != 'Super admin', function($a) use($roleInfo){
                $a->whereJsonContains('inform_to', (string)$roleInfo->id);
            })
            ->get(['notice_title', 'notice_message', 'publish_on', 'inform_to']);
        }

        if($onlineExamSettings->status == 1){
            $onlineExams = SmOnlineExam::with('class', 'section', 'subject')
            ->when($userRoleId == 2, function($s) use ($studentRecords){
                $class_ids = $studentRecords->pluck('class_id')->toArray();
                $section_ids = $studentRecords->pluck('section_id')->toArray();
                $s->whereIn('class_id', $class_ids);
                $s->orWhereIn('section_id', $section_ids);
            })
            ->when($userRoleId == 3, function($s) use ($childrenRecords){
                $s->whereIn('class_id', gv($childrenRecords,'class_id'));
                $s->orWhereIn('section_id', gv($childrenRecords,'section_id'));
            })
            ->when($userRoleId == 4, function ($t) use($teacherAccess){
                $t->whereIn('class_id', $teacherAccess);
                $t->orWhere('created_by', auth()->user()->staff->id);
            })
            ->get(['title', 'date', 'end_date_time', 'start_time', 'end_time', 'subject_id', 'class_id', 'section_id']);
        }

        if($lessonPlanSettings->status == 1){
            $lessonPlans = LessonPlanner::with('class', 'sectionName', 'subject')
            ->when($userRoleId == 2, function($s) use ($studentRecords){
                $class_ids = $studentRecords->pluck('class_id')->toArray();
                $section_ids = $studentRecords->pluck('section_id')->toArray();
                $s->whereIn('class_id', $class_ids);
                $s->orWhereIn('section_id', $section_ids);
            })
            ->when($userRoleId == 3, function($s) use ($childrenRecords){
                $s->whereIn('class_id', gv($childrenRecords,'class_id'));
                $s->orWhereIn('section_id', gv($childrenRecords,'section_id'));
            })
            ->when($userRoleId == 4, function ($t) use($teacherAccess){
                $t->whereIn('class_id', $teacherAccess);
                $t->orWhere('created_by', auth()->user()->staff->id);
            })
            ->get(['lesson_date', 'teacher_id', 'subject_id', 'class_id', 'section_id']);
        }

        if($leaveSettings->status == 1){
            $leaveDatas = SmLeaveRequest::with('user')
            ->when($roleInfo->name != 'Super admin' && $roleInfo->name != 'Parents', function($a){
                $a->where('staff_id', auth()->user()->id);
            })
            ->when($roleInfo->name == 'Parents', function($p) use($childrenUserids){
                $p->whereIn('staff_id', $childrenUserids);
            })
            ->where('approve_status', 'A')->get(['leave_from', 'leave_to', 'reason', 'staff_id']);
        }
        
        if($librarySettings->status == 1){
            $libraryDatas = SmBookIssue::with('user')
            ->when($roleInfo->name != 'Super admin', function($a){
                $a->where('member_id', auth()->user()->id);
            })
            ->where('issue_status', 'I')->get(['due_date', 'member_id', 'book_id']);
        }
        // CalenderQuery End
        
        
        $eventData= [];

        foreach($admissionQuries as $query){
            $eventData [] = [
                'title' => __('communicate.admission_query').'- '.$query->name,
                'name' => $query->name,
                'phone' => $query->phone,
                'email' => $query->email,
                'address' => $query->address,
                'start' => Carbon::parse($query->next_follow_up_date)->format('Y-m-d'),
                'end' => Carbon::parse($query->next_follow_up_date)->format('Y-m-d'),
                'endDate' => Carbon::parse($query->next_follow_up_date)->format('Y-m-d'),
                'route' => route('admission_query'),
                'textColor' => $admissionQuerySettings->font_color,
                'color' => $admissionQuerySettings->bg_color,
                'type' => 'admission_query',
            ];
        }    

        foreach($homeworks as $homework){
            $eventData [] = [
                'title' => __('communicate.homework').'- '.$homework->description,
                'class' => $homework->class->class_name ?? '',
                'section' => $homework->section->section_name ?? '',
                'subject' => $homework->subjects->subject_name ?? '',
                'description' => $homework->description,
                'start' => Carbon::parse($homework->submission_date)->format('Y-m-d'),
                'end' => Carbon::parse($homework->submission_date)->format('Y-m-d'),
                'endDate' => Carbon::parse($homework->submission_date)->format('Y-m-d'),
                'route' => route('homework-list'),
                'textColor' => $homeworkSettings->font_color,
                'color' => $homeworkSettings->bg_color,
                'type' => 'homework',
            ];
        }

        foreach($studyMaterials as $material){
            if ($material->content_type == 'as') {
                $type = __('study.assignment');
            } elseif ($material->content_type == 'st') {
                $type = __('study.study_material');
            } elseif ($material->content_type == 'sy') {
                $type = __('study.syllabus');
            } else {
                $type = __('study.other');
            }

            if ($material->available_for_admin == 1) {
                $avaiable = app('translator')->get('study.all_admins');
            } elseif ($material->available_for_all_classes == 1) {
                $avaiable = app('translator')->get('study.all_classes_student');
            } elseif ($material->classes != "" && $material->sections != "") {
                $avaiable = app('translator')->get('study.all_students_of') . " " . $material->classes->class_name . '->' . @$material->sections->section_name;
            } elseif ($material->classes != "" && $material->section == null) {
                $avaiable = app('translator')->get('study.all_students_of') . " " . $material->classes->class_name . '->' . app('translator')->get('study.all_sections');
            }else{
                $avaiable = app('translator')->get('study.all_students_of');
            }

            $eventData [] = [
                'title' => __('communicate.study_material').'- '.$material->content_title,
                'content_title' => $material->content_title,
                'content_type' => $type,
                'description' => $material->description,
                'avaiable' => $avaiable,
                'start' => Carbon::parse($material->upload_date)->format('Y-m-d'),
                'end' => Carbon::parse($material->upload_date)->format('Y-m-d'),
                'endDate' => Carbon::parse($material->upload_date)->format('Y-m-d'),
                'textColor' => $studyMaterialSettings->font_color,
                'color' => $studyMaterialSettings->bg_color,
                'type' => 'study_material',
            ];
        }
        

        foreach($allEvents as $event){
            $eventData [] = [
                'title' =>  __('communicate.event').'- '.$event->event_title,
                'content_title' => $event->event_title,
                'description' => $event->event_des,
                'location' => $event->event_location,
                'image' => $event->uplad_image_file,
                'start' => Carbon::parse($event->from_date)->format('Y-m-d'),
                'end' => Carbon::parse($event->to_date)->addDay(1)->format('Y-m-d'),
                'endDate' => Carbon::parse($event->to_date)->format('Y-m-d'),
                'link' => $event->url,
                'textColor' => $eventSettings->font_color,
                'color' => $eventSettings->bg_color,
                'type' => 'event',
            ];
        }

        foreach($holidays as $holiday) {
            $eventData [] = [
                'title' => __('communicate.holiday').'- '.$holiday->holiday_title,
                'title_content' => $holiday->holiday_title,
                'description' => $holiday->details,
                'start' => Carbon::parse($holiday->from_date)->format('Y-m-d'),
                'end' => Carbon::parse($holiday->to_date)->addDay(1)->format('Y-m-d'),
                'endDate' => Carbon::parse($holiday->to_date)->format('Y-m-d'),
                'image' => $holiday->upload_image_file,
                'textColor' => $holidaySettings->font_color,
                'color' => $holidaySettings->bg_color,
                'type' => 'holiday',
            ];
        }

        foreach($examRounines as $examRoutine) {
            $eventData [] = [
                'title' => __('exam.exam_schedule').'- '.@$examRoutine->examType->title ?? '',
                'class' => $examRoutine->class->class_name ?? '',
                'section' => $examRoutine->section->section_name ?? '',
                'subject' => $examRoutine->subject->subject_name ?? '',
                'exam_term' => $examRoutine->examType->title ?? '',
                'start_time' => Carbon::parse($examRoutine->start_time)->format('g:i A'),
                'end_time' => Carbon::parse($examRoutine->end_time)->format('g:i A'),
                'teacher' => $examRoutine->teacher->full_name ?? '',
                'room' => $examRoutine->classRoom->room_no ?? '',
                'start' => Carbon::parse($examRoutine->date)->format('Y-m-d'),
                'end' => Carbon::parse($examRoutine->date)->format('Y-m-d'),
                'endDate' => Carbon::parse($examRoutine->date)->format('Y-m-d'),
                'textColor' => $examSettings->font_color,
                'color' => $examSettings->bg_color,
                'type' => 'exam',
            ];
        }
        

        foreach($noticeBoards as $notice) {
            $eventData [] = [
                'title' => __('communicate.notice_board').'- '.$notice->notice_title,
                'title_content' => $notice->notice_title,
                'notice_message' => $notice->notice_message,
                'inform_to' => $this->getRoleNames($notice->inform_to)->implode(', ') ?? '',
                'start' => Carbon::parse($notice->publish_on)->format('Y-m-d'),
                'end' => Carbon::parse($notice->publish_on)->format('Y-m-d'),
                'endDate' => Carbon::parse($notice->publish_on)->format('Y-m-d'),
                'textColor' => $noticeBoardSettings->font_color,
                'color' => $noticeBoardSettings->bg_color,
                'type' => 'notice_board',
            ];
        }

        foreach($onlineExams as $onlineExam) {
            $eventData [] = [
                'title' => __('communicate.online_exam').'- '.$onlineExam->title,
                'title_content' => $onlineExam->title,
                'class' => $onlineExam->class->class_name ?? '',
                'section' => $onlineExam->section->section_name ?? '',
                'subject' => $onlineExam->subject->subject_name ?? '',
                'start_time' => Carbon::parse($onlineExam->start_time)->format('g:i A'),
                'end_time' => Carbon::parse($onlineExam->end_time)->format('g:i A'),
                'start' => Carbon::parse($onlineExam->date)->format('Y-m-d'),
                'end' => Carbon::parse($onlineExam->end_date_time)->addDay(1)->format('Y-m-d'),
                'endDate' => Carbon::parse($onlineExam->end_date_time)->format('Y-m-d'),
                'textColor' => $onlineExamSettings->font_color,
                'color' => $onlineExamSettings->bg_color,
                'type' => 'online_exam',
            ];
        }

        foreach($lessonPlans as $lessonPlan) {
            $eventData [] = [
                'title' => __('communicate.lesson_plan'),
                'class' => $lessonPlan->class->class_name ?? '',
                'section' => $lessonPlan->sectionName->section_name ?? '',
                'subject' => $lessonPlan->subject->subject_name ?? '',
                'teacher' => $lessonPlan->teacherName->full_name ?? '',
                'start' => Carbon::parse($lessonPlan->lesson_date)->format('Y-m-d'),
                'end' => Carbon::parse($lessonPlan->lesson_date)->format('Y-m-d'),
                'endDate' => Carbon::parse($lessonPlan->lesson_date)->format('Y-m-d'),
                'textColor' => $lessonPlanSettings->font_color,
                'color' => $lessonPlanSettings->bg_color,
                'type' => 'lesson_plan',
            ];
        }

        foreach($leaveDatas as $leave) {
            $eventData [] = [
                'title' => __('communicate.leave').'- '.$leave->user->full_name ?? '',
                'name' => $leave->user->full_name ?? '',
                'reason' => $leave->reason,
                'start' => Carbon::parse($leave->leave_from)->format('Y-m-d'),
                'end' => Carbon::parse($leave->leave_to)->addDay(1)->format('Y-m-d'),
                'endDate' => Carbon::parse($leave->leave_to)->format('Y-m-d'),
                'textColor' => $leaveSettings->font_color,
                'color' => $leaveSettings->bg_color,
                'type' => 'leave',
            ];
        }
        
        foreach($libraryDatas as $library) {
            $eventData [] = [
                'title' => __('communicate.library').'- '.$library->user->full_name ?? '',
                'book_name' => $library->books->book_title ?? '',
                'start' => Carbon::parse($library->due_date)->format('Y-m-d'),
                'end' => Carbon::parse($library->due_date)->format('Y-m-d'),
                'endDate' => Carbon::parse($library->due_date)->format('Y-m-d'),
                'textColor' => $librarySettings->font_color,
                'color' => $librarySettings->bg_color,
                'type' => 'library',
            ];
        }
        return $eventData;

        }catch(\Exception $e){
            return [];
        }
    }
}
