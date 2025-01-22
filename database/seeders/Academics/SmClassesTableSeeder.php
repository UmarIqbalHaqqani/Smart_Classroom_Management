<?php

namespace Database\Seeders\Academics;

use App\User;
use App\SmClass;
use App\SmStaff;
use App\SmParent;
use App\SmSection;
use App\SmStudent;
use App\SmSubject;
use App\SmAdmissionQuery;
use App\SmStaffAttendence;
use App\SmStudentAttendance;
use App\Models\StudentRecord;
use App\SmAssignClassTeacher;
use Illuminate\Database\Seeder;
use App\SmAdmissionQueryFollowup;

class SmClassesTableSeeder extends Seeder
{
    public $sections;
    public $subjects;

    public function __construct()
    {
        $this->sections = SmSection::all();
        $this->subjects = SmSubject::all();
    }

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run($school_id = 1, $academic_id = 1, $count = 1)
    {
        $sections = SmSection::where('school_id', $school_id)->where('academic_id', $academic_id)->get();
        $subjects = SmSubject::where('school_id', $school_id)->where('academic_id', $academic_id)->get();

        SmClass::factory()->times($count)->create([
            'school_id' => $school_id,
            'academic_id' => $academic_id
        ])->each(function ($class) use ($sections, $school_id) {
            $class_sections = [];
            foreach ($sections as $section) {
                $class_sections[] = [
                    'section_id' => $section->id,
                    'school_id' => $school_id,
                    'academic_id' => $class->academic_id,
                ];
                $i = 0;
                SmStudent::factory()->times(5)->create()->each(function ($student) use ($class, $section, $school_id) {

                    User::factory()->times(1)->create([
                        'role_id' => 2,
                        'email' => $student->email,
                        'username' => $student->email,
                        'school_id' => $school_id,
                    ])->each(function ($user) use ($student) {
                        $student->user_id = $user->id;
                        $student->save();
                    });

                    SmParent::factory()->times(1)->create([
                        'school_id' => $school_id,
                        'guardians_email' => 'guardian_' . $student->id . '@infixedu.com',
                    ])->each(function ($parent) use ($student, $school_id) {
                        $student->parent_id = $parent->id;
                        $student->save();
                        User::factory()->times(1)->create([
                            'role_id' => 3,
                            'email' => $parent->guardians_email,
                            'username' => $parent->guardians_email,
                            'school_id' => $school_id,
                        ])->each(function ($user) use ($parent) {
                            $parent->user_id = $user->id;
                            $parent->save();
                        });
                    });

                    $studentRecordStore = new StudentRecord();
                    $studentRecordStore->class_id = $class->id;
                    $studentRecordStore->section_id = $section->id;
                    $studentRecordStore->school_id = $school_id;
                    $studentRecordStore->academic_id = $class->academic_id;
                    $studentRecordStore->roll_no = $student->roll_no;
                    $studentRecordStore->session_id = $class->academic_id;
                    $studentRecordStore->is_default = 1;
                    $studentRecordStore->student_id = $student->id;
                    $studentRecordStore->save();

                    $attendance_type = ['P', 'L', 'A', 'F'];
                    foreach(lastOneMonthDates() as $date){
                        shuffle($attendance_type);
                        $studentAttendance = new SmStudentAttendance();
                        $studentAttendance->student_record_id = $studentRecordStore->id;
                        $studentAttendance->student_id = $studentRecordStore->student_id;
                        $studentAttendance->class_id = $studentRecordStore->class_id;
                        $studentAttendance->section_id = $studentRecordStore->section_id;
                        $studentAttendance->attendance_type = $attendance_type[0];
                        $studentAttendance->notes = $studentAttendance->attendance_type == "P" ? "Good" : "Bad";
                        $studentAttendance->attendance_date = $date;
                        $studentAttendance->school_id = $school_id;
                        $studentAttendance->academic_id = $studentRecordStore->academic_id;
                        $studentAttendance->save();
                    }
                });
            }
            $class_sections = $class->classSection()->createMany($class_sections);
            $assign_class_teachers = [];
            foreach ($class_sections as $class_section) {
                $assign_class_teachers[] = [
                    'class_id' => $class_section->class_id,
                    'section_id' => $class_section->section_id,
                    'academic_id' => $class_section->academic_id,
                    'school_id' => $school_id,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];

                User::factory()->times(1)->create([
                    'school_id' => $school_id,
                ])->each( function ($userStaff) use ($school_id) {
                    SmStaff::factory()->times(1)->create([
                        'user_id' => $userStaff->id,
                        'email' => $userStaff->email,
                        'first_name' => $userStaff->first_name,
                        'last_name' => $userStaff->last_name,
                        'full_name' => $userStaff->full_name,
                        'school_id' => $school_id,
                        'role_id' => 4,
                    ])->each(function($staff) use($school_id) {
                        $staff->staff_no = $staff->id;
                        $staff->mobile = '+8801234567'.$staff->id;
                        $staff->save();
                        
                        $attendance_type = ['P', 'L', 'A', 'F'];
                        foreach(lastOneMonthDates() as $date){
                            shuffle($attendance_type);
                            $attendanceStaff = new SmStaffAttendence();
                            $attendanceStaff->staff_id = $staff->id;
                            $attendanceStaff->school_id = $school_id;
                            $attendanceStaff->attendence_type = $attendance_type[0];
                            $attendanceStaff->notes = $attendanceStaff->attendance_type == "P" ? "Good" : "Bad";
                            $attendanceStaff->attendence_date = $date;
                            $attendanceStaff->save();
                        }
                    });
                });
            }

            SmAdmissionQuery::factory()->times(10)->create([
                'class' => $class->id,
                'school_id' => $school_id,
                'academic_id' => $class->academic_id,
            ])->each(function ($admission_query) use($school_id) {
                SmAdmissionQueryFollowup::factory()->times(random_int(5, 10))->create([
                    'admission_query_id' => $admission_query->id,
                    'school_id' => $school_id,
                    'academic_id' => $admission_query->academic_id,
                ]);
            });
        });
    }
}