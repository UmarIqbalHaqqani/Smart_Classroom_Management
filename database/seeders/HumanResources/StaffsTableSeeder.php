<?php

namespace Database\Seeders\HumanResources;

use App\User;
use App\SmRoute;
use App\SmStaff;
use App\SmStaffAttendence;
use App\Models\SmExpertTeacher;
use Illuminate\Database\Seeder;

class StaffsTableSeeder  extends Seeder
{

    public function run($school_id , $count = 10){
       
        $roles = [4, 5, 6, 7, 8, 9];

        foreach ($roles as $role_id) {
            User::factory()->times($count)->create([
                'school_id' => $school_id,
                'role_id' => $role_id,
            ])->each(function ($userStaff) use ($school_id, $role_id) {
                SmStaff::factory()->times(1)->create([
                    'user_id' => $userStaff->id,
                    'email' => $userStaff->email,
                    'first_name' => $userStaff->first_name,
                    'last_name' => $userStaff->last_name,
                    'full_name' => $userStaff->full_name,
                    'school_id' => $school_id,
                    'role_id' => $role_id,
                ])->each(function ($s) use ($school_id) {
                    $s->staff_no = $s->id;
                    $s->mobile = '+8801234567' . $s->id;
                    $s->save();

                    $attendance_type = ['P', 'L', 'A', 'F'];
                    foreach (lastOneMonthDates() as $date) {
                        shuffle($attendance_type);
                        $attendanceStaff = new SmStaffAttendence();
                        $attendanceStaff->staff_id = $s->id;
                        $attendanceStaff->school_id = $school_id;
                        $attendanceStaff->attendence_type = $attendance_type[0];
                        $attendanceStaff->notes = $attendanceStaff->attendence_type == "P" ? "Good" : "Bad";
                        $attendanceStaff->attendence_date = $date;
                        $attendanceStaff->save();
                    }
                });
            });
        }

        // User::factory()->times($count)->create([
        //     'school_id' => $school_id,
        // ])->each( function ($userStaff) use ($school_id) {
        //     SmStaff::factory()->times(1)->create([
        //         'user_id' => $userStaff->id,
        //         'email' => $userStaff->email,
        //         'first_name' => $userStaff->first_name,
        //         'last_name' => $userStaff->last_name,
        //         'full_name' => $userStaff->full_name,
        //         'school_id' => $school_id,
        //         'role_id' => $userStaff->role_id,
        //     ])->each(function($s) use($school_id){
        //         $s->staff_no = $s->id;
        //         $s->mobile = '+8801234567'.$s->id;
        //         $s->save();
                
        //         $i = 0;
        //         // SmExpertTeacher::factory()->times(1)->create([
        //         //     'staff_id' => $s->id,
        //         //     'created_by' => 1,
        //         //     'updated_by' => 1,
        //         //     'position' => $i++,
        //         // ]);

        //         $attendance_type = ['P', 'L', 'A', 'F'];
        //         foreach(lastOneMonthDates() as $date){
        //             shuffle($attendance_type);
        //             $attendanceStaff = new SmStaffAttendence();
        //             $attendanceStaff->staff_id = $s->id;
        //             $attendanceStaff->school_id = $school_id;
        //             $attendanceStaff->attendence_type = $attendance_type[0];
        //             $attendanceStaff->notes = $attendanceStaff->attendance_type == "P" ? "Good" : "Bad";
        //             $attendanceStaff->attendence_date = $date;
        //             $attendanceStaff->save();
        //         }
        //     });
        // });

        // User::factory()->times($count)->create([
        //     'school_id' => $school_id,
        // ])->each( function ($userStaff) use ($school_id) {
        //     SmStaff::factory()->times(1)->create([
        //         'user_id' => $userStaff->id,
        //         'email' => $userStaff->email,
        //         'first_name' => $userStaff->first_name,
        //         'last_name' => $userStaff->last_name,
        //         'full_name' => $userStaff->full_name,
        //         'school_id' => $school_id,
        //         'role_id' =>4,
        //     ])->each(function($s){
        //         $s->staff_no = $s->id;
        //         $s->save();
        //     });
        // });
    }

}