<?php

namespace Database\Seeders\FrontendCMS;

use App\Models\FrontExamRoutine;
use App\SmExamType;
use App\SmClass;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class FrontExamRoutineSeeder extends Seeder
{
    public function run($school_id = 1, $count = 5)
    {
        $smExamType = SmExamType::where('school_id', $school_id)->first();


        if (!$smExamType) {
            return;
        }

        $classes = SmClass::where('school_id', $school_id)->first();

        $sections = $classes->classSection;


        if ($sections->isEmpty()) {
            return;
        }

        foreach ($sections as $section) {
            FrontExamRoutine::create([
                'title' => $smExamType->title  .$classes->class_name.' - ('.$section->sectionName->section_name . ')',
                'publish_date' => date('Y-m-d'),
                'school_id' => $school_id,
                'result_file' => 'public/uploads/front_exam_routine/exam_routine_'.$classes->id.'_'.$section->id.'.pdf',
            ]);
        }
    }
    
}
