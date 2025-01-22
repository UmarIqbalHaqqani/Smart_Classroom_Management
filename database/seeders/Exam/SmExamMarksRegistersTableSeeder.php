<?php

namespace Database\Seeders\Exam;

use App\SmClass;
use App\SmStudent;
use App\SmClassSection;
use App\SmAssignSubject;
use Faker\Factory as Faker;
use App\SmExamMarksRegister;
use App\Models\StudentRecord;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\SmExam;
use App\SmExamSetup;
use App\YearCheck;
use App\SmResultStore;
use App\SmMarkStore;

class SmExamMarksRegistersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run($school_id, $academic_id)
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        $exam = new SmExam();
        $exam->exam_type_id = 1;
        $exam->class_id = 1;
        $exam->section_id = 1;
        $exam->subject_id = 1;
        $exam->exam_mark = 100;
        $exam->created_by = 1;
        $exam->created_at = YearCheck::getYear() . '-' . date('m-d h:i:s');
        $exam->school_id = 1;
        $exam->academic_id = 1;
        $exam->save();
        $exam->toArray();
    
        $ex_title = "First Term Exam";
        $ex_mark  = 100;
        $newSetupExam = new SmExamSetup();
        $newSetupExam->exam_id = $exam->id;
        $newSetupExam->class_id = 1;
        $newSetupExam->section_id = 1;
        $newSetupExam->subject_id =1;
        $newSetupExam->exam_term_id = 1;
        $newSetupExam->exam_title = $ex_title;
        $newSetupExam->exam_mark = $ex_mark;
        $newSetupExam->created_by= 1;
        $newSetupExam->created_at = YearCheck::getYear() . '-' . date('m-d h:i:s');
        $newSetupExam->school_id = 1;
        $newSetupExam->academic_id = 1;
        $newSetupExam->save();

        $marks_register = new SmMarkStore();
        $marks_register->exam_term_id = 1;
        $marks_register->class_id = 1;
        $marks_register->section_id = 1;
        $marks_register->subject_id = 1;
        $marks_register->student_id = 1;
        $marks_register->created_at = YearCheck::getYear() . '-' . date('m-d h:i:s');
        $marks_register->total_marks = 100;
        $marks_register->exam_setup_id = 1;
        $marks_register->student_record_id = 1;

        $marks_register->is_absent = 0;
         
        $marks_register->teacher_remarks = 'Good';

        $marks_register->created_at = YearCheck::getYear() . '-' . date('m-d h:i:s');
        $marks_register->school_id = 1;
        $marks_register->academic_id = 1;

        $marks_register->save();

        $result_record = new SmResultStore();
        $result_record->class_id = 1;
        $result_record->section_id = 1;
        $result_record->subject_id = 1;
        $result_record->exam_type_id = 1;
        $result_record->student_id = 1;
        $result_record->is_absent = 0;
        $result_record->total_marks     = 100;
        $result_record->total_gpa_point = 5;
        $result_record->total_gpa_grade = 'A+';
        $result_record->teacher_remarks = 'Good';
        $result_record->created_at = YearCheck::getYear() . '-' . date('m-d h:i:s');
        $result_record->school_id = 1;
        $result_record->student_record_id = 1;
        $result_record->academic_id = 1;
        $result_record->save();
    
        $faker = Faker::create();

        $classSection = SmClassSection::where('school_id',$school_id)->where('academic_id', $academic_id)->first();
        $students = StudentRecord::where('class_id', $classSection->class_id)->where('section_id', $classSection->section_id)->where('school_id',$school_id)->where('academic_id', $academic_id)->get();
        foreach ($students as $record) {
            $class_id = $record->class_id;
            $section_id = $record->section_id;
            $subjects = SmAssignSubject::where('school_id',$school_id)->where('academic_id', $academic_id)->where('class_id', $class_id)->where('section_id', $section_id)->get();
            
            foreach ($subjects as $subject) {
                $store = new SmExamMarksRegister();
                $store->exam_id = 1;
                $store->student_id = $record->student_id;
                $store->subject_id = $subject->subject_id;
                $store->obtained_marks = rand(40, 90);
                $store->exam_date = $faker->dateTime()->format('Y-m-d');
                $store->comments = $faker->realText($maxNbChars = 50, $indexSize = 2);
                $store->created_at = date('Y-m-d h:i:s');
                $store->save();
            } //end subject

            
        } //end student list
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
