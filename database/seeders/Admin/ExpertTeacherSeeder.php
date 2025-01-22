<?php

namespace Database\Seeders\Admin;

use App\Models\SmExpertTeacher;
use App\User;
use App\SmStaff;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class ExpertTeacherSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */

    public function run($school_id, $count = 4) {
        $staffData = [
            [
                'name'              => 'Jaylon',
                'designation'       => 1,
                'department'        => 1,
                'qualification'     => 'M.Ed., Ed.D., Teaching Certification',
                'gender'            => 1,
                'experience'        => '15 Years',
                'date_of_birth'     => '1966-01-01',
                'email'             => 'user_0@infixedu.com',
                'mobile'            => '+880123456701',
                'current_address'   => '1234 Elm Street, Springfield, IL 62704',
                'permanent_address' => '5678 Oak Avenue, Lincoln, NE 68508',
                'staff_photo'       => 'public/uploads/expert_teacher/teacher-1.jpg',
            ],
            [
                'name'              => 'Mr. Olivia',
                'designation'       => 5,
                'department'        => 1,
                'qualification'     => 'Masters',
                'gender'            => 1,
                'experience'        => '7 Years',
                'date_of_birth'     => '1986-01-01',
                'email'             => 'olivia@infixedu.com',
                'mobile'            => '+880123456702',
                'current_address'   => '789 Pine Street, Austin, TX 73301',
                'permanent_address' => '345 Maple Road, Denver, CO 80203',
                'staff_photo'       => 'public/uploads/expert_teacher/teacher-3.jpg',
            ],
            [
                'name'              => 'Mr. Ahmed',
                'designation'       => 5,
                'department'        => 1,
                'qualification'     => 'Masters',
                'gender'            => 1,
                'experience'        => '4 Years',
                'date_of_birth'     => '1991-01-01',
                'email'             => 'ahmed@infixedu.com',
                'mobile'            => '+880123456703',
                'current_address'   => '101 Apple Lane, Miami, FL 33101',
                'permanent_address' => '202 Banana Blvd, Orlando, FL 32801',
                'staff_photo'       => 'public/uploads/expert_teacher/teacher-2.jpg',
            ],
            [
                'name'              => 'Mr. Patel',
                'designation'       => 3,
                'department'        => 1,
                'qualification'     => 'Masters',
                'gender'            => 1,
                'experience'        => '6 Years',
                'date_of_birth'     => '1990-01-01',
                'email'             => 'patel@infixedu.com',
                'mobile'            => '+880123456704',
                'current_address'   => '303 Cherry Street, Dallas, TX 75201',
                'permanent_address' => '404 Date Avenue, Houston, TX 77001',
                'staff_photo'       => 'public/uploads/expert_teacher/teacher-12.jpeg',
            ],
        ];
    
        foreach (array_slice($staffData, 0, $count) as $index => $data) {
            $userStaff = User::factory()->create([
                'school_id' => $school_id,
                'email'     => $data['email'],
                'full_name' => $data['name'],
                'role_id'   => 4,
                'password'  => Hash::make('123456'),
            ]);
    
            $staff = SmStaff::factory()->create([
                'user_id'           => $userStaff->id,
                'email'             => $data['email'],
                'first_name'        => $data['name'],
                'last_name'         => '',
                'full_name'         => $data['name'],
                'school_id'         => $school_id,
                'designation_id'    => $data['designation'],
                'department_id'     => $data['department'],
                'qualification'     => $data['qualification'],
                'experience'        => $data['experience'],
                'date_of_birth'     => $data['date_of_birth'],
                'gender_id'         => $data['gender'],
                'role_id'           => 4,
                'staff_photo'       => $data['staff_photo'],
                'mobile'            => $data['mobile'],
                'current_address'   => $data['current_address'],
                'permanent_address' => $data['permanent_address'],
            ]);
    
            $staff->staff_no = $staff->id;
            $staff->save();
    
            SmExpertTeacher::factory()->create([
                'staff_id'   => $staff->id,
                'created_by' => 1,
                'updated_by' => 1,
                'position'   => $index,
            ]);
        }
    }
    
}
