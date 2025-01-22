<?php

namespace Database\Seeders;

use App\SmBookIssue;
use App\SmGeneralSettings;
use App\SmLanguage;
use App\SmLeaveRequest;
use App\SmLeaveType;
use App\SmLibraryMember;
use App\SmOnlineExam;
use App\SmQuestionGroup;
use App\SmStudent;
use Carbon\Carbon;
use App\SmStaff;
use App\SmQuestionBank;
use Faker\Factory as Faker;
use App\SmOnlineExamQuestionAssign;
use App\SmStudentTakeOnlineExamQuestion;
use App\SmStudentTakeOnlineExam;
use Modules\Chat\Entities\Conversation;
use Modules\Chat\Entities\GroupMessageRecipient;
use Nwidart\Modules\Facades\Module;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Modules\Chat\Entities\Group;
use Modules\Chat\Entities\GroupUser;
use Modules\Wallet\Entities\WalletTransaction;
use Modules\Jitsi\Entities\JitsiVirtualClass;
use Modules\Jitsi\Entities\JitsiMeeting;
use App\SmSubjectAttendance;
use Illuminate\Support\Facades\Schema;

class DemoDataSeeder extends Seeder
{

    public function run($count = 5)
    {
        # Message for Student
        $students = SmStudent::all();

        $students->each(function ($student) {
            $student->update([
                'route_list_id' => 1,
            ]);
        });

        for ($i = 1; $i <= 2; $i++) {
            $group = Group::create([
                'name' => 'Chat Group - ' . $i,
            ]);

            foreach ($students as $student) {
                GroupUser::create([
                    'group_id' => $group->id,
                    'user_id' => $student->user_id,
                    'added_by' => 1,
                    'role' => 2,
                ]);
            }
        }

        $selectedStudents = $students->random(min(5, $students->count()));

        $studentUsers = $selectedStudents->pluck('user_id')->toArray();

        foreach ($studentUsers as $fromUserId) {
            foreach ($studentUsers as $toUserId) {
                if ($fromUserId !== $toUserId) {
                    $conversationHi = Conversation::create([
                        'message' => 'Hi',
                        'original_file_name' => null,
                    ]);

                    $conversationHello = Conversation::create([
                        'message' => 'Hello',
                        'original_file_name' => null,
                    ]);

                    $group = Group::first();

                    GroupMessageRecipient::create([
                        'user_id' => $toUserId,
                        'conversation_id' => $conversationHi->id,
                        'group_id' => $group->id,
                        'read_at' => null,
                    ]);

                    GroupMessageRecipient::create([
                        'user_id' => $fromUserId,
                        'conversation_id' => $conversationHello->id,
                        'group_id' => $group->id,
                        'read_at' => null,
                    ]);
                }
            }
        }

        foreach ($studentUsers as $fromUserId) {
            foreach ($studentUsers as $toUserId) {
                if ($fromUserId !== $toUserId) {
                    Conversation::create([
                        'from_id'   => $fromUserId,
                        'to_id'     => $toUserId,
                        'message'   => 'Hi',
                        'original_file_name' => null,
                    ]);
    
                    Conversation::create([
                        'from_id'   => $toUserId,
                        'to_id'     => $fromUserId,
                        'message'   => 'Hello',
                        'original_file_name' => null,
                    ]);
                }
            }
        }

        # Message For Teacher
        $teachers = SmStaff::where('role_id', 4)->get();
        $selectedTeachers = $teachers->random(min(5, $teachers->count()));

        $teachertUsers = $selectedTeachers->pluck('user_id')->toArray();

        foreach ($teachertUsers as $fromUserId) {
            foreach ($teachertUsers as $toUserId) {
                if ($fromUserId !== $toUserId) {
                    $conversationHi = Conversation::create([
                        'message' => 'Hi Teacher',
                        'original_file_name' => null,
                    ]);

                    $conversationHello = Conversation::create([
                        'message' => 'Hello Teacher',
                        'original_file_name' => null,
                    ]);

                    $group = Group::first();

                    GroupMessageRecipient::create([
                        'user_id' => $toUserId,
                        'conversation_id' => $conversationHi->id,
                        'group_id' => $group->id,
                        'read_at' => null,
                    ]);

                    GroupMessageRecipient::create([
                        'user_id' => $fromUserId,
                        'conversation_id' => $conversationHello->id,
                        'group_id' => $group->id,
                        'read_at' => null,
                    ]);
                }
            }
        }

        foreach ($teachertUsers as $fromUserId) {
            foreach ($teachertUsers as $toUserId) {
                if ($fromUserId !== $toUserId) {
                    Conversation::create([
                        'from_id'   => $fromUserId,
                        'to_id'     => $toUserId,
                        'message'   => 'Hi Teacher',
                        'original_file_name' => null,
                    ]);
    
                    Conversation::create([
                        'from_id'   => $toUserId,
                        'to_id'     => $fromUserId,
                        'message'   => 'Hello Teacher',
                        'original_file_name' => null,
                    ]);
                }
            }
        }

        // #Wallet Transaction
        foreach ($students as $student) {
            $addPayment = new WalletTransaction();
            $addPayment->amount = rand(1000, 5000);
            $addPayment->payment_method = 'cheque';
            $addPayment->bank_id = null;
            $addPayment->note = 'Wallet Transaction';
            $addPayment->file = null;
            $addPayment->type = 'diposit';
            $addPayment->user_id = $student->user_id;
            $addPayment->school_id = 1;
            $addPayment->academic_id = 1;
            $addPayment->status = 'approve';
            $addPayment->save();
        }

        $leaveTypes = SmLeaveType::all();
        foreach ($leaveTypes as $leaveType) {
            $students = SmStudent::where('school_id', 1)->get();
        
            foreach ($students as $student) {
                foreach (['P', 'A', 'C'] as $status) {
                    $storeRequest = new SmLeaveRequest();
                    $storeRequest->type_id = $leaveType->id;
                    $storeRequest->leave_define_id = 1;
                    $storeRequest->staff_id = $student->id;
                    $storeRequest->role_id = 2;
                    $storeRequest->apply_date = Carbon::now()->format('Y-m-d');
                    $storeRequest->leave_from = Carbon::now()->format('Y-m-d');
                    $storeRequest->leave_to = Carbon::now()->addDays(2)->format('Y-m-d');
                    $storeRequest->reason = 'Seeder Leave';
                    $storeRequest->note = 'Seeder Leave';
                    $storeRequest->file = "public/uploads/leave_request/sample.pdf";
                    $storeRequest->approve_status = $status;
                    $storeRequest->school_id = 1;
                    $storeRequest->academic_id = 1;
                    $storeRequest->save();
                }
            }
        }

        # Teacher Leave
        $leaveTypes = SmLeaveType::all();
        foreach ($leaveTypes as $leaveType) {
            $teachers = SmStaff::where('role_id', 4)->get();
        
            foreach ($teachers as $teacher) {
                foreach (['P', 'A', 'C'] as $status) {
                    $storeRequest = new SmLeaveRequest();
                    $storeRequest->type_id = $leaveType->id;
                    $storeRequest->leave_define_id = 1;
                    $storeRequest->staff_id = $teacher->id;
                    $storeRequest->role_id = 4;
                    $storeRequest->apply_date = Carbon::now()->format('Y-m-d');
                    $storeRequest->leave_from = Carbon::now()->format('Y-m-d');
                    $storeRequest->leave_to = Carbon::now()->addDays(2)->format('Y-m-d');
                    $storeRequest->reason = 'Seeder Leave';
                    $storeRequest->note = 'Seeder Leave';
                    $storeRequest->file = "public/uploads/leave_request/sample.pdf";
                    $storeRequest->approve_status = $status;
                    $storeRequest->school_id = 1;
                    $storeRequest->academic_id = 1;
                    $storeRequest->save();
                }
            }
        }

        $notices = [
            [
                'notice_title' => 'Midterm Exams 2024: Important Announcement',
                'notice_message' => "Dear Students, this is to inform you that the test exams for the 2024 academic year are scheduled to begin on Dec 10, 2024, and will end on March 20, 2024. The detailed timetable for the exams will be provided shortly. We advise all students to prepare thoroughly and approach their studies with focus and dedication. Please ensure to bring all necessary materials on exam days and follow the school’s examination guidelines. Let’s aim for excellent results in the upcoming exams!",
                'notice_date' => date("Y-m-d", strtotime('2024-12-08')),
                'publish_on' => date("Y-m-d", strtotime('2024-12-08')),
                'inform_to' => "[2]",
                'is_published' => 1,
            ],
            [
                'notice_title' => 'Announcement: Summer Vacation 2024 Scheduled from July 1 to July 31',
                'notice_message' => "We are delighted to announce that InfixEdu School will be on summer vacation from July 1, 2024, to July 31, 2024. This extended break offers an excellent opportunity for students to relax, enjoy time with family, and explore personal interests. We encourage our students to make the most of this time by engaging in fun and educational activities, and to come back refreshed for the new term. Please note that school activities will resume on August 1, 2024. We wish everyone a joyful and relaxing vacation!",
                'notice_date' => date("Y-m-d", strtotime('2024-05-25')),
                'publish_on' => date("Y-m-d", strtotime('2024-05-25')),
                'inform_to' => "[2]",
                'is_published' => 1,
            ],
            [
                'notice_title' => 'Midterm Exams 2024: Important Announcement for Teachers',
                'notice_message' => "Dear Teachers, this is to inform you that the midterm exams for the 2024 academic year are scheduled to begin on Dec 10, 2024, and will end on March 20, 2024. The detailed timetable for the exams will be provided shortly. We advise all teachers to prepare thoroughly by finalizing lesson plans and supporting materials to ensure students are well-prepared. Please be available during exam days to assist with invigilation and any required administrative tasks. Let’s work together to ensure a smooth and successful examination period!",
                'notice_date' => date("Y-m-d", strtotime('2024-12-08')),
                'publish_on' => date("Y-m-d", strtotime('2024-12-08')),
                'inform_to' => "[4]",
                'is_published' => 1,
            ],
            [
                'notice_title' => 'Announcement: Summer Vacation 2024 Scheduled from July 1 to July 31',
                'notice_message' => "We are delighted to announce that InfixEdu School will be on summer vacation from July 1, 2024, to July 31, 2024. This extended break provides an excellent opportunity for teachers to relax, plan for the upcoming term, and engage in personal or professional development activities. We encourage you to use this time effectively and return refreshed for the new term. Please note that school activities will resume on August 1, 2024. We wish all our teachers a joyful and restful vacation!",
                'notice_date' => date("Y-m-d", strtotime('2024-05-25')),
                'publish_on' => date("Y-m-d", strtotime('2024-05-25')),
                'inform_to' => "[4]",
                'is_published' => 1,
            ],
            [
                'notice_title' => 'Midterm Exams 2024: Important Announcement for Admin',
                'notice_message' => "Dear Admin, this is to inform you that the midterm exams for the 2024 academic year are scheduled to begin on Dec 10, 2024, and will end on March 20, 2024. The detailed timetable for the exams will be provided shortly. We request all administrative staff to coordinate with teachers to finalize the exam schedule and ensure all necessary arrangements, such as hall bookings, seating plans, and materials, are in place. Let’s work collaboratively to ensure a smooth and successful examination process!",
                'notice_date' => date("Y-m-d", strtotime('2024-12-08')),
                'publish_on' => date("Y-m-d", strtotime('2024-12-08')),
                'inform_to' => "[5]",
                'is_published' => 1,
            ],
            [
                'notice_title' => 'Announcement: Summer Vacation 2024 Scheduled from July 1 to July 31',
                'notice_message' => "We are pleased to announce that InfixEdu School will be on summer vacation from July 1, 2024, to July 31, 2024. This break offers a chance for the administrative team to review operations, plan for the upcoming term, and implement any necessary updates or improvements to the school’s facilities and systems. Please ensure that any pending administrative tasks are completed before the vacation period begins. Regular school activities will resume on August 1, 2024. Wishing all admin staff a productive and refreshing break!",
                'notice_date' => date("Y-m-d", strtotime('2024-05-25')),
                'publish_on' => date("Y-m-d", strtotime('2024-05-25')),
                'inform_to' => "[5]",
                'is_published' => 1,
            ],            
        ];
    
        foreach ($notices as $notice) {
            DB::table('sm_notice_boards')->insert($notice);
        }

        # Book Library  Student
        foreach ($students as $student) {
            $members = new SmLibraryMember();
            $members->member_type = 2;
            $members->student_staff_id = $student->user_id;
            $members->member_ud_id = rand(1000, 9999);
            $members->school_id = 1;
            $members->created_by = 1;
            $members->academic_id = 1;
            $members->save();

            $bookIssue = new SmBookIssue();
            $bookIssue->book_id = rand(1, 10);
            $bookIssue->member_id = $student->user_id;
            $bookIssue->given_date = date('Y-m-d');
            $bookIssue->due_date = date('Y-m-d', strtotime('+10 days'));
            $bookIssue->issue_status = 'I';
            $bookIssue->created_by = 1;
            $bookIssue->save();
        }

        # Book Library  Student
        foreach ($teachers as $teacher) {
            $members = new SmLibraryMember();
            $members->member_type = 4;
            $members->student_staff_id = $teacher->user_id;
            $members->member_ud_id = rand(1000, 9999);
            $members->school_id = 1;
            $members->created_by = 1;
            $members->academic_id = 1;
            $members->save();

            $bookIssue = new SmBookIssue();
            $bookIssue->book_id = rand(1, 10);
            $bookIssue->member_id = $teacher->user_id;
            $bookIssue->given_date = date('Y-m-d');
            $bookIssue->due_date = date('Y-m-d', strtotime('+10 days'));
            $bookIssue->issue_status = 'I';
            $bookIssue->created_by = 1;
            $bookIssue->save();
        }

        $language_details = DB::table('languages')->where('id', 3)->first();

        if (!empty($language_details)) {
            $sms_languages = new SmLanguage();
            $sms_languages->language_name = $language_details->name;
            $sms_languages->language_universal = $language_details->code;
            $sms_languages->native = $language_details->native;
            $sms_languages->lang_id = $language_details->id;
            $sms_languages->active_status = '0';
            $sms_languages->school_id = 1;
            $sms_languages->save();

            if ($language_details->code != 'en') {
                File::copyDirectory(base_path('/resources/lang/en'), base_path('/resources/lang/' . $language_details->code));
                $modules = Module::all();
                foreach ($modules as $module) {
                    File::copyDirectory(module_path($module->getName()) . '/Resources/lang/en', module_path($module->getName()) . '/Resources/lang/' . $language_details->code);
                }
            }
            Cache::forget('translations');
        }
        $faker = Faker::create();
        $sm_question_groups = SmQuestionGroup::get();

        foreach ($sm_question_groups as $sm_question_group) {
            $online_question                    = new SmQuestionBank();
            $online_question->type              = "T";
            $online_question->q_group_id        = $sm_question_group->id;
            $online_question->class_id          = 1;
            $online_question->section_id        = 1;
            $online_question->marks             = 5;
            $online_question->question          = $faker->sentence(rand(7, 15));
            $online_question->school_id         = 1;
            $online_question->academic_id       = 1;
            $online_question->trueFalse         = "T";
            $online_question->save();
        }

        $sm_question_banks = SmQuestionBank::get();

        $online_exams = SmOnlineExam::get();
        foreach($sm_question_banks as $question) {
            foreach($online_exams as $exam) {
                $assign = new SmOnlineExamQuestionAssign();
                $assign->online_exam_id = $exam->id;
                $assign->question_bank_id = $question->id;
                $assign->school_id = 1;
                $assign->academic_id = 1;
                $assign->save();
            }
        }
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        $exam_question = new SmStudentTakeOnlineExamQuestion();
        $exam_question->take_online_exam_id = 1;
        $exam_question->question_bank_id = 1;
        $exam_question->trueFalse = 'T';
        $exam_question->school_id = 1;
        $exam_question->academic_id =  1;
        $exam_question->created_by = 1;
        $exam_question->updated_by = 1; 
        $exam_question->save();
        
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $take_online_exam = new SmStudentTakeOnlineExam();
        $take_online_exam->online_exam_id = 1;
        $take_online_exam->student_done = 1;
        $take_online_exam->status = 2;
        $take_online_exam->total_marks = 5;
        $take_online_exam->student_id = 1;
        $take_online_exam->record_id = 1;
        $take_online_exam->school_id = 1;
        $take_online_exam->academic_id =1;
        $take_online_exam->save();

        SmGeneralSettings::query()->update([
            'jitsi' => 1
        ]);

        if (Schema::hasTable('jitsi_virtual_classes')) {

            $date = now()->format('m/d/Y');
            $time = now()->format('g:i A');

            $datetime = $date . " " . $time;
            $datetime = strtotime($datetime);
            $start_date = now()->toDateTimeString();
            $duration = 60;
        
            $local_virtual = JitsiVirtualClass::create([
                'meeting_id' => date('ymd' . rand(0, 100)),                 
                'class_id' => 1,
                'section_id' => 1,
                'topic' => "Demo Jitsi Meeting",
                'description' =>  "Demo Purpose Jitsi Meeting",
                'date' => $date,
                'time' => $time,
                'datetime' => $datetime,
                'duration' => $duration,
                'attached_file'=> null,
                'time_start_before' => 10,
                'start_time' =>  Carbon::parse($start_date)->toDateTimeString(),
                'end_time' =>  Carbon::parse($start_date)->addMinute($duration)->toDateTimeString(),
                'created_by' => 1,
            ]);
            $sm_staff = SmStaff::where('role_id', 4)->first();
            $staff_user_id = $sm_staff->user_id;
            $local_virtual->teachers()->attach($staff_user_id);


            $local_meeting = JitsiMeeting::create([
                'meeting_id' => date('ymdhmi'),
                'member_type' => 4,  
                'instructor_id' => 1,           
                'topic' => "Demo Jitsi Meeting",
                'date' => $date,
                'time' => $time,
                'datetime' => $datetime,
                'description' => "Demo Purpose Jitsi Meeting",
                'file' => null,
                'duration' => $duration,
                'time_start_before'=> 10,
                'start_time' =>  Carbon::parse($start_date)->toDateTimeString(),
                'end_time' =>  Carbon::parse($start_date)->addMinute($duration)->toDateTimeString(),
                'created_by' => 1,
            ]);

            $staffs = SmStaff::where('role_id', 4)->limit(2)->get();
            $staff_ids = $staffs->pluck('user_id')->toArray();
            $local_meeting->participates()->attach($staff_ids);
        }

        $attendance = new SmSubjectAttendance();
        $attendance->attendance_type = "H";
        $attendance->notes = "Holiday";
        $attendance->attendance_date = date('Y-m-d');
        $attendance->student_id = 1;
        $attendance->subject_id = 1;
        
        $attendance->student_record_id = 1;
        $attendance->class_id = 1;
        $attendance->section_id = 1;
        
        $attendance->academic_id = 1;
        $attendance->school_id = 1;
        $attendance->save();
    }
}
