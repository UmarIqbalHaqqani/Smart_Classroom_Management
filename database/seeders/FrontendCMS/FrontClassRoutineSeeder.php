<?php

namespace Database\Seeders\FrontendCMS;

use App\Models\FrontClassRoutine;
use App\SmClass;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class FrontClassRoutineSeeder extends Seeder
{
    public function run($school_id = 1, $count = 5)
    {
        $classes = SmClass::where('school_id', $school_id)->first();

        if (!$classes) {
            $this->command->info('No classes found for school_id: '.$school_id);
            return;
        }

        $sections = $classes->classSection;

        if ($sections->isEmpty()) {
            $this->command->info('No sections found for class: '.$classes->class_name);
            return;
        }

        foreach ($sections as $section) {
            FrontClassRoutine::create([
                'title' => 'Class Routine - '.$classes->class_name.' - ('.$section->sectionName->section_name . ')',
                'publish_date' => date('Y-m-d'),
                'school_id' => $school_id,
                'result_file' => 'public/uploads/front_class_routine/class_routine_'.$classes->id.'_'.$section->id.'.pdf',
            ]);
        }

        $this->command->info('Class routines created successfully.');
    }
}
