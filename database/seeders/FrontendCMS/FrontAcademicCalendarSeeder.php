<?php

namespace Database\Seeders\FrontendCMS;

use App\Models\FrontAcademicCalendar;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class FrontAcademicCalendarSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run($school_id = 1, $count = 5)
    {
        for ($i = 1; $i <= $count; $i++) {
            FrontAcademicCalendar::create([
                'title'         => "Academic Calendar $i",
                'publish_date'  => date('Y-m-d'),
                'calendar_file' => 'public/uploads/academic_calendar/academic_calendar_'.$i.'.pdf',
                'school_id'     => $school_id,
            ]);
        }
    }
}
