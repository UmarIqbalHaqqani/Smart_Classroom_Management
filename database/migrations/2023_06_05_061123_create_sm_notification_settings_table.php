<?php

use App\Models\SmNotificationSetting;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSmNotificationSettingsTable extends Migration
{
    public function up()
    {
        Schema::create('sm_notification_settings', function (Blueprint $table) {
            $table->id();
            $table->string('event')->nullable();
            $table->string('destination')->nullable()->comment('E=email, S=SMS, W=web, A=app');
            $table->string('recipient')->nullable();
            $table->string('subject')->nullable();
            $table->longText('template')->nullable();
            $table->integer('school_id')->nullable()->default(1)->unsigned();
            $table->foreign('school_id')->references('id')->on('sm_schools')->onDelete('cascade');
            $table->string('shortcode')->nullable();
            $table->timestamps();
        });


        $all_events = [
            [
                'event' => 'Assign_Class_Teacher',
                'destination' => [
                    "Email" => 1, 
                    "SMS" => 1, 
                    "Web" => 1, 
                    "App" => 1
                ],
                'recipient' => [
                    "Student" => 1,
                    "Parent" => 1,
                    'Teacher' => 1
                ],
                'subject' => [
                    "Student"=> "Assigned Classes For Teachers" ,
                    "Parent"=> "Assigned Classes For Teachers" ,
                    "Teacher" => "Assigned Classes For Teachers"
                ],

                'template' => [
                    "Student" => [
                        "Email" => "Dear [student_name],
                        This Class: [class], Section: [section] has been assigned to the Teacher: [teacher_name]. For any query, please contact with admin." ,
                        "SMS" => "Dear [student_name],
                        This Class: [class], Section: [section] has been assigned to the Teacher: [teacher_name]. For any query, please contact with admin." ,
                        "Web" => "Dear [student_name],
                        This Class: [class], Section: [section] has been assigned to the Teacher: [teacher_name]. For any query, please contact with admin." ,
                        "App" => "Dear [student_name],
                        This Class: [class], Section: [section] has been assigned to the Teacher: [teacher_name]. For any query, please contact with admin." ,
                    ],
                    "Parent"=> [
                        "Email" => "Dear [parent_name],
                        This Class: [class], Section: [section] has been assigned to the Teacher: [teacher_name] for your child [student_name]. For any query, please contact with admin." ,
                        "SMS" => "Dear [parent_name],
                        This Class: [class], Section: [section] has been assigned to the Teacher: [teacher_name] for your child [student_name]. For any query, please contact with admin." ,
                        "Web" => "Dear [parent_name],
                        This Class: [class], Section: [section] has been assigned to the Teacher: [teacher_name] for your child [student_name]. For any query, please contact with admin." ,
                        "App" => "Dear [parent_name],
                        This Class: [class], Section: [section] has been assigned to the Teacher: [teacher_name] for your child [student_name]. For any query, please contact with admin." ,
                    ],
                    "Teacher"=> [
                        "Email" => "Dear [teacher_name],
                        You have been assigned to Class: [class], Section: [section]. For any query, please contact with admin." ,
                        "SMS" => "Dear [teacher_name],
                        You have been assigned to Class: [class], Section: [section]. For any query, please contact with admin." ,
                        "Web" => "Dear [teacher_name],
                        You have been assigned to Class: [class], Section: [section]. For any query, please contact with admin." ,
                        "App" => "Dear [teacher_name],
                        You have been assigned to Class: [class], Section: [section]. For any query, please contact with admin." ,
                    ],
                ],

                'shortcode' => [
                    "Student" => "[student_name], [class], [section], [teacher_name]",
                    "Parent" => "[parent_name], [student_name], [class], [section], [teacher_name]",
                    "Teacher" => "[teacher_name], [class], [section]"
                ]
            ],
            [
                'event' => 'Contact_Us',
                'destination' => [
                    "Email" => 1, 
                    "SMS" => 1, 
                    "Web" => 1, 
                    "App" => 1
                ],
                'recipient' => [
                    'Super admin' => 1,
                ],
                'subject' => [
                    "Super admin"=> "Contact Us",
                ],

                'template' => [
                    "Super admin"=> [
                        "Email" => "Dear [admin_name],
                        You have a new message from a visitor. Check out the details." ,
                        "SMS" => "Dear [admin_name],
                        You have a new message from a visitor. Check out the details." ,
                        "Web" => "Dear [admin_name],
                        You have a new message from a visitor. Check out the details." ,
                        "App" => "Dear [admin_name],
                        You have a new message from a visitor. Check out the details." ,
                    ],
                ],

                'shortcode' => [
                    "Super admin" => "[admin_name], [class], [section], [teacher_name]"
                ]
            ],
            [
                'event' => 'Assign_Subject',
                'destination' => [
                    "Email" => 1, 
                    "SMS" => 1, 
                    "Web" => 1, 
                    "App" => 1
                ],
                'recipient' => [
                    "Student" => 1,
                    "Parent" => 1,
                    'Teacher' => 1
                ],
                'subject' => [
                    "Student"=> "New assigned subject." ,
                    "Parent"=> "New assigned subject." ,
                    "Teacher" => "New assigned subject."
                ],

                'template' => [
                    "Student" => [
                        "Email" => "Dear [student_name],
                        A subject [subject] has been assigned to the Teacher: [teacher_name]. For any query, please contact with admin." ,
                        "SMS" => "Dear [student_name],
                        A subject [subject] has been assigned to the Teacher: [teacher_name]. For any query, please contact with admin." ,
                        "Web" => "Dear [student_name],
                        A subject [subject] has been assigned to the Teacher: [teacher_name]. For any query, please contact with admin." ,
                        "App" => "Dear [student_name],
                        A subject [subject] has been assigned to the Teacher: [teacher_name]. For any query, please contact with admin." ,
                    ],
                    "Parent"=> [
                        "Email" => "Dear [parent_name],
                        A subject [subject] has been assigned to the Teacher: [teacher_name] for you child [student_name]. For any query, please contact with admin." ,
                        "SMS" => "Dear [parent_name],
                        A subject [subject] has been assigned to the Teacher: [teacher_name] for you child [student_name]. For any query, please contact with admin." ,
                        "Web" => "Dear [parent_name],
                        A subject [subject] has been assigned to the Teacher: [teacher_name] for you child [student_name]. For any query, please contact with admin." ,
                        "App" => "Dear [parent_name],
                        A subject [subject] has been assigned to the Teacher: [teacher_name] for you child [student_name]. For any query, please contact with admin." ,
                    ],
                    "Teacher"=> [
                        "Email" => "Dear [teacher_name],
                        A subject [subject] has been assigned to you. For any query, please contact with admin." ,
                        "SMS" => "Dear [teacher_name],
                        A subject [subject] has been assigned to you. For any query, please contact with admin." ,
                        "Web" => "Dear [teacher_name],
                        A subject [subject] has been assigned to you. For any query, please contact with admin." ,
                        "App" => "Dear [teacher_name],
                        A subject [subject] has been assigned to you. For any query, please contact with admin." ,
                    ],
                ],

                'shortcode' => [
                    "Student" => "[student_name], [subject], [teacher_name]",
                    "Parent" => "[parent_name], [student_name], [subject], [teacher_name]",
                    "Teacher" => "[teacher_name], [subject]"
                ]
            ],
            [
                'event' => 'Assignment',
                'destination' => [
                    "Email" => 1, 
                    "SMS" => 1, 
                    "Web" => 1, 
                    "App" => 1
                ],
                'recipient' => [
                    "Student" => 1,
                    "Parent" => 1
                ],
                'subject' => [
                    "Student"=> "New assignment." ,
                    "Parent"=> "New assignment." 
                ],

                'template' => [
                    "Student" => [
                        "Email" => "Dear [student_name],
                        A Assignment: [assignment] has been created for Class: [class], Section: [section], Subject: [subject]. For any query, please contact with admin." ,
                        "SMS" => "Dear [student_name],
                        A Assignment: [assignment] has been created for Class: [class], Section: [section], Subject: [subject]. For any query, please contact with admin." ,
                        "Web" => "Dear [student_name],
                        A Assignment: [assignment] has been created for Class: [class], Section: [section], Subject: [subject]. For any query, please contact with admin." ,
                        "App" => "Dear [student_name],
                        A Assignment: [assignment] has been created for Class: [class], Section: [section], Subject: [subject]. For any query, please contact with admin." ,
                    ],
                    "Parent"=> [
                        "Email" => "Dear [parent_name],
                        A Assignment: [assignment] has been created for Class: [class], Section: [section], Subject: [subject]. For any query, please contact with admin." ,
                        "SMS" => "Dear [parent_name],
                        A Assignment: [assignment] has been created for Class: [class], Section: [section], Subject: [subject]. For any query, please contact with admin." ,
                        "Web" => "Dear [parent_name],
                        A Assignment: [assignment] has been created for Class: [class], Section: [section], Subject: [subject]. For any query, please contact with admin." ,
                        "App" => "Dear [parent_name],
                        A Assignment: [assignment] has been created for Class: [class], Section: [section], Subject: [subject]. For any query, please contact with admin." ,
                    ],
                ],

                'shortcode' => [
                    "Student" => "[student_name], [assignment], [class], [section], [subject]",
                    "Parent" => "[parent_name], [student_name], [assignment], [class], [section], [subject]"
                ]
            ],
            [
                'event' => 'Syllabus',
                'destination' => [
                    "Email" => 1, 
                    "SMS" => 1, 
                    "Web" => 1, 
                    "App" => 1
                ],
                'recipient' => [
                    "Student" => 1,
                    "Parent" => 1
                ],
                'subject' => [
                    "Student"=> "New syllabus content." ,
                    "Parent"=> "New syllabus content." 
                ],

                'template' => [
                    "Student" => [
                        "Email" => "Dear [student_name],
                        A new Syllabus: [syllabus] content has been created for Class: [class], Section: [section], Subject: [subject]. For any query, please contact with admin." ,
                        "SMS" => "Dear [student_name],
                        A new Syllabus: [syllabus] content has been created for Class: [class], Section: [section], Subject: [subject]. For any query, please contact with admin." ,
                        "Web" => "Dear [student_name],
                        A new Syllabus: [syllabus] content has been created for Class: [class], Section: [section], Subject: [subject]. For any query, please contact with admin." ,
                        "App" => "Dear [student_name],
                        A new Syllabus: [syllabus] content has been created for Class: [class], Section: [section], Subject: [subject]. For any query, please contact with admin." ,
                    ],
                    "Parent"=> [
                        "Email" => "Dear [parent_name],
                        A new Syllabus: [syllabus] content has been created for Class: [class], Section: [section], Subject: [subject]. For any query, please contact with admin." ,
                        "SMS" => "Dear [parent_name],
                        A new Syllabus: [syllabus] content has been created for Class: [class], Section: [section], Subject: [subject]. For any query, please contact with admin." ,
                        "Web" => "Dear [parent_name],
                        A new Syllabus: [syllabus] content has been created for Class: [class], Section: [section], Subject: [subject]. For any query, please contact with admin." ,
                        "App" => "Dear [parent_name],
                        A new Syllabus: [syllabus] content has been created for Class: [class], Section: [section], Subject: [subject]. For any query, please contact with admin." ,
                    ]
                ],

                'shortcode' => [
                    "Student" => "[student_name], [syllabus], [class], [section]",
                    "Parent" => "[parent_name], [student_name], [syllabus], [class], [section]"
                ]
            ],
            [
                'event' => 'Lesson_Plan',
                'destination' => [
                    "Email" => 1, 
                    "SMS" => 1, 
                    "Web" => 1, 
                    "App" => 1
                ],
                'recipient' => [
                    "Student" => 1,
                    "Parent" => 1,
                    'Teacher' => 1
                ],
                'subject' => [
                    "Student"=> "New lesson plan." ,
                    "Parent"=> "New lesson plan." ,
                    "Teacher" => "New lesson plan."
                ],

                'template' => [
                    "Student" => [
                        "Email" => "Dear [student_name],
                        A new Lesson Plan: [lesson_plan] has been created for Class: [class], Section: [section], Subject: [subject]. For any query, please contact with admin." ,
                        "SMS" => "Dear [student_name],
                        A new Lesson Plan: [lesson_plan] has been created for Class: [class], Section: [section], Subject: [subject]. For any query, please contact with admin." ,
                        "Web" => "Dear [student_name],
                        A new Lesson Plan: [lesson_plan] has been created for Class: [class], Section: [section], Subject: [subject]. For any query, please contact with admin." ,
                        "App" => "Dear [student_name],
                        A new Lesson Plan: [lesson_plan] has been created for Class: [class], Section: [section], Subject: [subject]. For any query, please contact with admin." ,
                    ],
                    "Parent"=> [
                        "Email" => "Dear [parent_name],
                        A new Lesson Plan: [lesson_plan] has been created for Class: [class], Section: [section], Subject: [subject]. For any query, please contact with admin." ,
                        "SMS" => "Dear [parent_name],
                        A new Lesson Plan: [lesson_plan] has been created for Class: [class], Section: [section], Subject: [subject]. For any query, please contact with admin." ,
                        "Web" => "Dear [parent_name],
                        A new Lesson Plan: [lesson_plan] has been created for Class: [class], Section: [section], Subject: [subject]. For any query, please contact with admin." ,
                        "App" => "Dear [parent_name],
                        A new Lesson Plan: [lesson_plan] has been created for Class: [class], Section: [section], Subject: [subject]. For any query, please contact with admin." ,
                    ],
                    "Teacher"=> [
                        "Email" => "Dear [teacher_name],
                        A new Lesson Plan: [lesson_plan] has been created for Class: [class], Section: [section], Subject: [subject]. For any query, please contact with admin." ,
                        "SMS" => "Dear [teacher_name],
                        A new Lesson Plan: [lesson_plan] has been created for Class: [class], Section: [section], Subject: [subject]. For any query, please contact with admin." ,
                        "Web" => "Dear [teacher_name],
                        A new Lesson Plan: [lesson_plan] has been created for Class: [class], Section: [section], Subject: [subject]. For any query, please contact with admin." ,
                        "App" => "Dear [teacher_name],
                        A new Lesson Plan: [lesson_plan] has been created for Class: [class], Section: [section], Subject: [subject]. For any query, please contact with admin." ,
                    ],
                ],

                'shortcode' => [
                    "Student" => "[student_name], [lesson_plan], [class], [section], [subject]",
                    "Parent" => "[parent_name], [student_name], [lesson_plan], [class], [section], [subejct]",
                    "Teacher" => "[teacher_name]",
                ]
            ],
            [
                'event' => 'Other_Downloads',
                'destination' => [
                    "Email" => 1, 
                    "SMS" => 1, 
                    "Web" => 1, 
                    "App" => 1
                ],
                'recipient' => [
                    "Student" => 1,
                    "Parent" => 1
                ],
                'subject' => [
                    "Student"=> "New downloadables." ,
                    "Parent"=> "New downloadables." 
                ],

                'template' => [
                    "Student" => [
                        "Email" => "Dear [student_name],
                        A new Other Downloads: [other_downloads] has been created for Class: [class], Section: [section], Subject: [subject]. For any query, please contact with admin." ,
                        "SMS" => "Dear [student_name],
                        A new Other Downloads: [other_downloads] has been created for Class: [class], Section: [section], Subject: [subject]. For any query, please contact with admin." ,
                        "Web" => "Dear [student_name],
                        A new Other Downloads: [other_downloads] has been created for Class: [class], Section: [section], Subject: [subject]. For any query, please contact with admin." ,
                        "App" => "Dear [student_name],
                        A new Other Downloads: [other_downloads] has been created for Class: [class], Section: [section], Subject: [subject]. For any query, please contact with admin." ,
                    ],
                    "Parent"=> [
                        "Email" => "Dear [parent_name],
                        A new Other Downloads: [other_downloads] has been created for Class: [class], Section: [section], Subject: [subject]. For any query, please contact with admin." ,
                        "SMS" => "Dear [parent_name],
                        A new Other Downloads: [other_downloads] has been created for Class: [class], Section: [section], Subject: [subject]. For any query, please contact with admin." ,
                        "Web" => "Dear [parent_name],
                        A new Other Downloads: [other_downloads] has been created for Class: [class], Section: [section], Subject: [subject]. For any query, please contact with admin." ,
                        "App" => "Dear [parent_name],
                        A new Other Downloads: [other_downloads] has been created for Class: [class], Section: [section], Subject: [subject]. For any query, please contact with admin." ,
                    ],
                ],

                'shortcode' => [
                    "Student" => "[student_name], [other_downloads], [class], [section], [subject]",
                    "Parent" => "[parent_name], [student_name], [other_downloads], [class], [section], [subject]"
                ]
            ],
            [
                'event' => 'Student_Admission',
                'destination' => [
                    "Email" => 1, 
                    "SMS" => 1, 
                    "Web" => 1, 
                    "App" => 1
                ],
                'recipient' => [
                    "Student" => 1,
                    "Parent" => 1,
                    'Super admin' => 1,
                    'Teacher' => 1
                ],
                'subject' => [
                    "Student"=> "New Student Admission." ,
                    "Parent"=> "New Student Admission." ,
                    "Super admin"=> "New Student Admission.",
                    "Teacher" => "New Student Admission."
                ],

                'template' => [
                    "Student" => [
                        "Email" => "Dear [student_name],
                        Your admission was successful. For any query, please contact with admin." ,
                        "SMS" => "Dear [student_name],
                        Your admission was successful. For any query, please contact with admin." ,
                        "Web" => "Dear [student_name],
                        Your admission was successful. For any query, please contact with admin." ,
                        "App" => "Dear [student_name],
                        Your admission was successful. For any query, please contact with admin." ,
                    ],
                    "Parent"=> [
                        "Email" => "Dear [parent_name],
                        Your child's admission was successful. For any query, please contact with admin." ,
                        "SMS" => "Dear [parent_name],
                        Your child's admission was successful. For any query, please contact with admin." ,
                        "Web" => "Dear [parent_name],
                        Your child's admission was successful. For any query, please contact with admin." ,
                        "App" => "Dear [parent_name],
                        Your child's admission was successful. For any query, please contact with admin." ,
                    ],
                    "Super admin"=> [
                        "Email" => "Dear [admin_name],
                        There's a new successful Student Admission." ,
                        "SMS" => "Dear [admin_name],
                        There's a new successful Student Admission." ,
                        "Web" => "Dear [admin_name],
                        There's a new successful Student Admission." ,
                        "App" => "Dear [admin_name],
                        There's a new successful Student Admission." ,
                    ],
                    "Teacher"=> [
                        "Email" => "Dear [teacher_name],
                        There's a new successful Student Admission. For any query, please contact with admin." ,
                        "SMS" => "Dear [teacher_name],
                        There's a new successful Student Admission. For any query, please contact with admin." ,
                        "Web" => "Dear [teacher_name],
                        There's a new successful Student Admission. For any query, please contact with admin." ,
                        "App" => "Dear [teacher_name],
                        There's a new successful Student Admission. For any query, please contact with admin." ,
                    ],
                ],

                'shortcode' => [
                    "Student" => "[student_name]",
                    "Parent" => "[parent_name]",
                    "Super admin" => "[admin_name]",
                    "Teacher" => "[teacher_name]"
                ]
            ],
            [
                'event' => 'Multi_Class',
                'destination' => [
                    "Email" => 1, 
                    "SMS" => 1, 
                    "Web" => 1, 
                    "App" => 1
                ],
                'recipient' => [
                    "Student" => 1,
                    "Parent" => 1,
                    'Teacher' => 1
                ],
                'subject' => [
                    "Student"=> "Multi class assigned." ,
                    "Parent"=> "Multi class assigned." ,
                    "Teacher" => "Multi class assigned."
                ],

                'template' => [
                    "Student" => [
                        "Email" => "Dear [student_name],
                        You have been assigned to multiple classes [class]([section]), [class]([section]). For any query, please contact with admin." ,
                        "SMS" => "Dear [student_name],
                        You have been assigned to multiple classes [class]([section]), [class]([section]). For any query, please contact with admin." ,
                        "Web" => "Dear [student_name],
                        You have been assigned to multiple classes [class]([section]), [class]([section]). For any query, please contact with admin." ,
                        "App" => "Dear [student_name],
                        You have been assigned to multiple classes [class]([section]), [class]([section]). For any query, please contact with admin." ,
                    ],
                    "Parent"=> [
                        "Email" => "Dear [parent_name],
                        Your child have been assigned to multiple classes [class]([section]), [class]([section]). For any query, please contact with admin." ,
                        "SMS" => "Dear [parent_name],
                        Your child have been assigned to multiple classes [class]([section]), [class]([section]). For any query, please contact with admin." ,
                        "Web" => "Dear [parent_name],
                        Your child have been assigned to multiple classes [class]([section]), [class]([section]). For any query, please contact with admin." ,
                        "App" => "Dear [parent_name],
                        Your child have been assigned to multiple classes [class]([section]), [class]([section]). For any query, please contact with admin." ,
                    ],
                    "Teacher"=> [
                        "Email" => "Dear [teacher_name],
                        A Student: [student_name] have been assigned to multiple classes [class]([section]), [class]([section]). For any query, please contact with admin." ,
                        "SMS" => "Dear [teacher_name],
                        A Student: [student_name] have been assigned to multiple classes [class]([section]), [class]([section]). For any query, please contact with admin." ,
                        "Web" => "Dear [teacher_name],
                        A Student: [student_name] have been assigned to multiple classes [class]([section]), [class]([section]). For any query, please contact with admin." ,
                        "App" => "Dear [teacher_name],
                        A Student: [student_name] have been assigned to multiple classes [class]([section]), [class]([section]). For any query, please contact with admin." ,
                    ],
                ],

                'shortcode' => [
                    "Student" => "[student_name], [class], [section]",
                    "Parent" => "[parent_name], [student_name], [class], [section]",
                    "Teacher" => "[teacher_name], [class], [section]"
                ]
            ],
            [
                'event' => 'Student_Attendance',
                'destination' => [
                    "Email" => 1, 
                    "SMS" => 1, 
                    "Web" => 1, 
                    "App" => 1
                ],
                'recipient' => [
                    "Student" => 1,
                    "Parent" => 1
                ],
                'subject' => [
                    "Student"=> "Student Attendance." ,
                    "Parent"=> "Student Attendance." 
                ],

                'template' => [
                    "Student" => [
                        "Email" => "Dear [student_name],
                        Your attendance was listed [attendance_type] today for Class: [class], Section: [section]. For any query, please contact with admin." ,
                        "SMS" => "Dear [student_name],
                        Your attendance was listed [attendance_type] today for Class: [class], Section: [section]. For any query, please contact with admin." ,
                        "Web" => "Dear [student_name],
                        Your attendance was listed [attendance_type] today for Class: [class], Section: [section]. For any query, please contact with admin." ,
                        "App" => "Dear [student_name],
                        Your attendance was listed [attendance_type] today for Class: [class], Section: [section]. For any query, please contact with admin." ,
                    ],
                    "Parent"=> [
                        "Email" => "Dear [parent_name],
                        Your child's attendance was listed [attendance_type] today for Class: [class], Section: [section]. For any query, please contact with admin." ,
                        "SMS" => "Dear [parent_name],
                        Your child's attendance was listed [attendance_type] today for Class: [class], Section: [section]. For any query, please contact with admin." ,
                        "Web" => "Dear [parent_name],
                        Your child's attendance was listed [attendance_type] today for Class: [class], Section: [section]. For any query, please contact with admin." ,
                        "App" => "Dear [parent_name],
                        Your child's attendance was listed [attendance_type] today for Class: [class], Section: [section]. For any query, please contact with admin." ,
                    ],
                ],

                'shortcode' => [
                    "Student" => "[student_name], [class], [section], [attendance_type]",
                    "Parent" => "[parent_name], [student_name], [class], [section], [attendance_type]"
                ]
            ],
            [
                'event' => 'Student_Promote',
                'destination' => [
                    "Email" => 1, 
                    "SMS" => 1, 
                    "Web" => 1, 
                    "App" => 1
                ],
                'recipient' => [
                    "Student" => 1,
                    "Parent" => 1,
                    'Teacher' => 1
                ],
                'subject' => [
                    "Student"=> "Student Promotion." ,
                    "Parent"=> "Student Promotion." ,
                    "Teacher" => "Student Promotion."
                ],

                'template' => [
                    "Student" => [
                        "Email" => "Dear [student_name],
                        You have been promoted to Class: [class], Section: [section]. For any query, please contact with admin." ,
                        "SMS" => "Dear [student_name],
                        You have been promoted to Class: [class], Section: [section]. For any query, please contact with admin." ,
                        "Web" => "Dear [student_name],
                        You have been promoted to Class: [class], Section: [section]. For any query, please contact with admin." ,
                        "App" => "Dear [student_name],
                        You have been promoted to Class: [class], Section: [section]. For any query, please contact with admin." ,
                    ],
                    "Parent"=> [
                        "Email" => "Dear [parent_name],
                        Your child has been promoted to Class: [class], Section: [section]. For any query, please contact with admin." ,
                        "SMS" => "Dear [parent_name],
                        Your child has been promoted to Class: [class], Section: [section]. For any query, please contact with admin." ,
                        "Web" => "Dear [parent_name],
                        Your child has been promoted to Class: [class], Section: [section]. For any query, please contact with admin." ,
                        "App" => "Dear [parent_name],
                        Your child has been promoted to Class: [class], Section: [section]. For any query, please contact with admin." ,
                    ],
                    "Teacher"=> [
                        "Email" => "Dear [teacher_name],
                        The Student: [student_name] has been promoted to Class: [class], Section: [section]. For any query, please contact with admin." ,
                        "SMS" => "Dear [teacher_name],
                        The Student: [student_name] has been promoted to Class: [class], Section: [section]. For any query, please contact with admin." ,
                        "Web" => "Dear [teacher_name],
                        The Student: [student_name] has been promoted to Class: [class], Section: [section]. For any query, please contact with admin." ,
                        "App" => "Dear [teacher_name],
                        The Student: [student_name] has been promoted to Class: [class], Section: [section]. For any query, please contact with admin." ,
                    ],
                ],

                'shortcode' => [
                    "Student" => "[student_name], [class], [section]",
                    "Parent" => "[parent_name], [student_name], [class], [section]",
                    "Teacher" => "[teacher_name], [class], [section]"
                ]
            ],
            [
                'event' => 'Enable/Disable_Student',
                'destination' => [
                    "Email" => 1, 
                    "SMS" => 1, 
                    "Web" => 1, 
                    "App" => 1
                ],
                'recipient' => [
                    "Student" => 1,
                    "Parent" => 1,
                    'Super admin' => 1,
                    'Teacher' => 1
                ],
                'subject' => [
                    "Student"=> "Enable/Disable Student." ,
                    "Parent"=> "Enable/Disable Student." ,
                    "Super admin"=> "Enable/Disable Student.",
                    "Teacher" => "Enable/Disable Student."
                ],

                'template' => [
                    "Student" => [
                        "Email" => "Dear [student_name],
                        Your account has been Enabled/Disabled. For any query, please contact with admin." ,
                        "SMS" => "Dear [student_name],
                        Your account has been Enabled/Disabled. For any query, please contact with admin." ,
                        "Web" => "Dear [student_name],
                        Your account has been Enabled/Disabled. For any query, please contact with admin." ,
                        "App" => "Dear [student_name],
                        Your account has been Enabled/Disabled. For any query, please contact with admin." ,
                    ],
                    "Parent"=> [
                        "Email" => "Dear [parent_name],
                        Your child's account has been Enabled/Disabled. For any query, please contact with admin." ,
                        "SMS" => "Dear [parent_name],
                        Your child's account has been Enabled/Disabled. For any query, please contact with admin." ,
                        "Web" => "Dear [parent_name],
                        Your child's account has been Enabled/Disabled. For any query, please contact with admin." ,
                        "App" => "Dear [parent_name],
                        Your child's account has been Enabled/Disabled. For any query, please contact with admin." ,
                    ],
                    "Super admin"=> [
                        "Email" => "Dear [admin_name],
                        A Student: [student_name]'s account has been Enabled/Disabled." ,
                        "SMS" => "Dear [admin_name],
                        A Student: [student_name]'s account has been Enabled/Disabled." ,
                        "Web" => "Dear [admin_name],
                        A Student: [student_name]'s account has been Enabled/Disabled." ,
                        "App" => "Dear [admin_name],
                        A Student: [student_name]'s account has been Enabled/Disabled." ,
                    ],
                    "Teacher"=> [
                        "Email" => "Dear [teacher_name],
                        A Student: [student_name]'s account has been Enabled/Disabled. For any query, please contact with admin." ,
                        "SMS" => "Dear [teacher_name],
                        A Student: [student_name]'s account has been Enabled/Disabled. For any query, please contact with admin." ,
                        "Web" => "Dear [teacher_name],
                        A Student: [student_name]'s account has been Enabled/Disabled. For any query, please contact with admin." ,
                        "App" => "Dear [teacher_name],
                        A Student: [student_name]'s account has been Enabled/Disabled. For any query, please contact with admin." ,
                    ],
                ],

                'shortcode' => [
                    "Student" => "[student_name]",
                    "Parent" => "[parent_name], [student_name]",
                    "Super admin" => "[admin_name], [student_name]",
                    "Teacher" => "[teacher_name], [student_name]"
                ]
            ],
            [
                'event' => 'Subject_Wise_Attendance',
                'destination' => [
                    "Email" => 1, 
                    "SMS" => 1, 
                    "Web" => 1, 
                    "App" => 1
                ],
                'recipient' => [
                    "Student" => 1,
                    "Parent" => 1
                ],
                'subject' => [
                    "Student"=> "Subject Wise Attendance." ,
                    "Parent"=> "Subject Wise Attendance." 
                ],

                'template' => [
                    "Student" => [
                        "Email" => "Dear [student_name],
                        Your attendance was listed [attendance_type] today for Subject: [subject]. For any query, please contact with admin." ,
                        "SMS" => "Dear [student_name],
                        Your attendance was listed [attendance_type] today for Subject: [subject]. For any query, please contact with admin." ,
                        "Web" => "Dear [student_name],
                        Your attendance was listed [attendance_type] today for Subject: [subject]. For any query, please contact with admin." ,
                        "App" => "Dear [student_name],
                        Your attendance was listed [attendance_type] today for Subject: [subject]. For any query, please contact with admin." ,
                    ],
                    "Parent"=> [
                        "Email" => "Dear [parent_name],
                        Your child's attendance was listed [attendance_type] today for Subject: [subject]. For any query, please contact with admin." ,
                        "SMS" => "Dear [parent_name],
                        Your child's attendance was listed [attendance_type] today for Subject: [subject]. For any query, please contact with admin." ,
                        "Web" => "Dear [parent_name],
                        Your child's attendance was listed [attendance_type] today for Subject: [subject]. For any query, please contact with admin." ,
                        "App" => "Dear [parent_name],
                        Your child's attendance was listed [attendance_type] today for Subject: [subject]. For any query, please contact with admin." ,
                    ]
                ],

                'shortcode' => [
                    "Student" => "[student_name], [subject], [attendance_type]",
                    "Parent" => "[parent_name], [student_name], [subject], [attendance_type]",
                ]
            ],
            [
                'event' => 'Fees_Assign',
                'destination' => [
                    "Email" => 1, 
                    "SMS" => 1, 
                    "Web" => 1, 
                    "App" => 1
                ],
                'recipient' => [
                    "Student" => 1,
                    "Parent" => 1
                ],
                'subject' => [
                    "Student"=> "New Fees Assigned." ,
                    "Parent"=> "New Fees Assigned."
                ],

                'template' => [
                    "Student" => [
                        "Email" => "Dear [student_name],
                        A new Fees: [fees] has been assigned for you. For any query, please contact with admin." ,
                        "SMS" => "Dear [student_name],
                        A new Fees: [fees] has been assigned for you. For any query, please contact with admin." ,
                        "Web" => "Dear [student_name],
                        A new Fees: [fees] has been assigned for you. For any query, please contact with admin." ,
                        "App" => "Dear [student_name],
                        A new Fees: [fees] has been assigned for you. For any query, please contact with admin." ,
                    ],
                    "Parent"=> [
                        "Email" => "Dear [parent_name],
                        A new Fees: [fees] has been assigned for your child [student_name]. For any query, please contact with admin." ,
                        "SMS" => "Dear [parent_name],
                        A new Fees: [fees] has been assigned for your child [student_name]. For any query, please contact with admin." ,
                        "Web" => "Dear [parent_name],
                        A new Fees: [fees] has been assigned for your child [student_name]. For any query, please contact with admin." ,
                        "App" => "Dear [parent_name],
                        A new Fees: [fees] has been assigned for your child [student_name]. For any query, please contact with admin." ,
                    ]
                ],

                'shortcode' => [
                    "Student" => "[student_name], [fees]",
                    "Parent" => "[parent_name], [student_name], [fees]"
                ]
            ],
            [
                'event' => 'Fees_Payment',
                'destination' => [
                    "Email" => 1, 
                    "SMS" => 1, 
                    "Web" => 1, 
                    "App" => 1
                ],
                'recipient' => [
                    "Student" => 1,
                    "Parent" => 1,
                    'Super admin' => 1,
                ],
                'subject' => [
                    "Student"=> "New Fees Payment." ,
                    "Parent"=> "New Fees Payment." ,
                    "Super admin"=> "New Fees Payment.",
                ],

                'template' => [
                    "Student" => [
                        "Email" => "Dear [student_name],
                        Your fees payment was successful for the Fees: [fees]. For any query, please contact with admin." ,
                        "SMS" => "Dear [student_name],
                        Your fees payment was successful for the Fees: [fees]. For any query, please contact with admin." ,
                        "Web" => "Dear [student_name],
                        Your fees payment was successful for the Fees: [fees]. For any query, please contact with admin." ,
                        "App" => "Dear [student_name],
                        Your fees payment was successful for the Fees: [fees]. For any query, please contact with admin." ,
                    ],
                    "Parent"=> [
                        "Email" => "Dear [parent_name],
                        Your fees payment was successful for the Fees: [fees] of your child [student_name]. For any query, please contact with admin." ,
                        "SMS" => "Dear [parent_name],
                        Your fees payment was successful for the Fees: [fees] of your child [student_name]. For any query, please contact with admin." ,
                        "Web" => "Dear [parent_name],
                        Your fees payment was successful for the Fees: [fees] of your child [student_name]. For any query, please contact with admin." ,
                        "App" => "Dear [parent_name],
                        Your fees payment was successful for the Fees: [fees] of your child [student_name]. For any query, please contact with admin." ,
                    ],
                    "Super admin"=> [
                        "Email" => "Dear [admin_name],
                        A fees payment was successful for the Fees: [fees] of the Student: [student_name]." ,
                        "SMS" => "Dear [admin_name],
                        A fees payment was successful for the Fees: [fees] of the Student: [student_name]." ,
                        "Web" => "Dear [admin_name],
                        A fees payment was successful for the Fees: [fees] of the Student: [student_name]." ,
                        "App" => "Dear [admin_name],
                        A fees payment was successful for the Fees: [fees] of the Student: [student_name]." ,
                    ],
                ],

                'shortcode' => [
                    "Student" => "[student_name], [fees]",
                    "Parent" => "[parent_name], [student_name], [fees]",
                    "Super admin" => "[teacher_name], [fees]",
                ]
            ],
            [
                'event' => 'Fees_Reminder',
                'destination' => [
                    "Email" => 1, 
                    "SMS" => 1, 
                    "Web" => 1, 
                    "App" => 1
                ],
                'recipient' => [
                    "Student" => 1,
                    "Parent" => 1,
                    'Super admin' => 1,
                ],
                'subject' => [
                    "Student"=> "Fees Reminder." ,
                    "Parent"=> "Fees Reminder." ,
                    "Super admin"=> "Fees Reminder.",
                ],

                'template' => [
                    "Student" => [
                        "Email" => "Dear [student_name],
                        You have a fees payment due for the Fees: [fees]. For any query, please contact with admin." ,
                        "SMS" => "Dear [student_name],
                        You have a fees payment due for the Fees: [fees]. For any query, please contact with admin." ,
                        "Web" => "Dear [student_name],
                        You have a fees payment due for the Fees: [fees]. For any query, please contact with admin." ,
                        "App" => "Dear [student_name],
                        You have a fees payment due for the Fees: [fees]. For any query, please contact with admin." ,
                    ],
                    "Parent"=> [
                        "Email" => "Dear [parent_name],
                        Your child [student_name] have a fees payment due for the Fees: [fees]. For any query, please contact with admin." ,
                        "SMS" => "Dear [parent_name],
                        Your child [student_name] have a fees payment due for the Fees: [fees]. For any query, please contact with admin." ,
                        "Web" => "Dear [parent_name],
                        Your child [student_name] have a fees payment due for the Fees: [fees]. For any query, please contact with admin." ,
                        "App" => "Dear [parent_name],
                        Your child [student_name] have a fees payment due for the Fees: [fees]. For any query, please contact with admin." ,
                    ],
                    "Super admin"=> [
                        "Email" => "Dear [admin_name],
                        A Student: [student_name] have a fees payment due for the Fees: [fees]." ,
                        "SMS" => "Dear [admin_name],
                        A Student: [student_name] have a fees payment due for the Fees: [fees]." ,
                        "Web" => "Dear [admin_name],
                        A Student: [student_name] have a fees payment due for the Fees: [fees]." ,
                        "App" => "Dear [admin_name],
                        A Student: [student_name] have a fees payment due for the Fees: [fees]." ,
                    ],
                ],

                'shortcode' => [
                    "Student" => "[student_name], [fees]",
                    "Parent" => "[parent_name], [student_name], [fees]",
                    "Super admin" => "[admin_name], [fees]"
                ]
            ],
            [
                'event' => 'Assign_homework',
                'destination' => [
                    "Email" => 1, 
                    "SMS" => 1, 
                    "Web" => 1, 
                    "App" => 1
                ],
                'recipient' => [
                    "Student" => 1,
                    "Parent" => 1
                ],
                'subject' => [
                    "Student"=> "New Homework." ,
                    "Parent"=> "New Homework."
                ],

                'template' => [
                    "Student" => [
                        "Email" => "Dear [student_name],
                        You have a new Homework for Subject: [subject]. For any query, please contact with admin." ,
                        "SMS" => "Dear [student_name],
                        You have a new Homework for Subject: [subject]. For any query, please contact with admin." ,
                        "Web" => "Dear [student_name],
                        You have a new Homework for Subject: [subject]. For any query, please contact with admin." ,
                        "App" => "Dear [student_name],
                        You have a new Homework for Subject: [subject]. For any query, please contact with admin." ,
                    ],
                    "Parent"=> [
                        "Email" => "Dear [parent_name],
                        Your child [student_name] have a new Homework for Subject: [subject]. For any query, please contact with admin." ,
                        "SMS" => "Dear [parent_name],
                        Your child [student_name] have a new Homework for Subject: [subject]. For any query, please contact with admin." ,
                        "Web" => "Dear [parent_name],
                        Your child [student_name] have a new Homework for Subject: [subject]. For any query, please contact with admin." ,
                        "App" => "Dear [parent_name],
                        Your child [student_name] have a new Homework for Subject: [subject]. For any query, please contact with admin." ,
                    ],
                ],

                'shortcode' => [
                    "Student" => "[student_name], [subject]",
                    "Parent" => "[parent_name], [student_name], [subject]",
                ]
            ],
            [
                'event' => 'Add_Library_Member',
                'destination' => [
                    "Email" => 1, 
                    "SMS" => 1, 
                    "Web" => 1, 
                    "App" => 1
                ],
                'recipient' => [
                    "Student" => 1,
                    "Parent" => 1,
                ],
                'subject' => [
                    "Student"=> "New Library Member." ,
                    "Parent"=> "New Library Member." ,
                ],

                'template' => [
                    "Student" => [
                        "Email" => "Dear [student_name],
                        You have been registered as a library member today. For any query, please contact with admin." ,
                        "SMS" => "Dear [student_name],
                        You have been registered as a library member today. For any query, please contact with admin." ,
                        "Web" => "Dear [student_name],
                        You have been registered as a library member today. For any query, please contact with admin." ,
                        "App" => "Dear [student_name],
                        You have been registered as a library member today. For any query, please contact with admin." ,
                    ],
                    "Parent"=> [
                        "Email" => "Dear [parent_name],
                        Your child [student_name] has been registered as a library member today. For any query, please contact with admin." ,
                        "SMS" => "Dear [parent_name],
                        Your child [student_name] has been registered as a library member today. For any query, please contact with admin." ,
                        "Web" => "Dear [parent_name],
                        Your child [student_name] has been registered as a library member today. For any query, please contact with admin." ,
                        "App" => "Dear [parent_name],
                        Your child [student_name] has been registered as a library member today. For any query, please contact with admin." ,
                    ],
                ],

                'shortcode' => [
                    "Student" => "[student_name]",
                    "Parent" => "[parent_name]"
                ]
            ],
            [
                'event' => 'Issue/Return_Book',
                'destination' => [
                    "Email" => 1, 
                    "SMS" => 1, 
                    "Web" => 1, 
                    "App" => 1
                ],
                'recipient' => [
                    "Student" => 1,
                    "Parent" => 1,
                    'Super admin' => 1,
                ],
                'subject' => [
                    "Student"=> "Issue/Return Book." ,
                    "Parent"=> "Issue/Return Book." ,
                    "Super admin"=> "Issue/Return Book.",
                ],

                'template' => [
                    "Student" => [
                        "Email" => "Dear [student_name],
                        You have Issued/Returned the Book: [book] at [date]. For any query, please contact with admin." ,
                        "SMS" => "Dear [student_name],
                        You have Issued/Returned the Book: [book] at [date]. For any query, please contact with admin." ,
                        "Web" => "Dear [student_name],
                        You have Issued/Returned the Book: [book] at [date]. For any query, please contact with admin." ,
                        "App" => "Dear [student_name],
                        You have Issued/Returned the Book: [book] at [date]. For any query, please contact with admin." ,
                    ],
                    "Parent"=> [
                        "Email" => "Dear [parent_name],
                        Your child has Issued/Returned the Book: [book] at [date]. For any query, please contact with admin." ,
                        "SMS" => "Dear [parent_name],
                        Your child has Issued/Returned the Book: [book] at [date]. For any query, please contact with admin." ,
                        "Web" => "Dear [parent_name],
                        Your child has Issued/Returned the Book: [book] at [date]. For any query, please contact with admin." ,
                        "App" => "Dear [parent_name],
                        Your child has Issued/Returned the Book: [book] at [date]. For any query, please contact with admin." ,
                    ],
                    "Super admin"=> [
                        "Email" => "Dear [admin_name],
                        A Student: [student_name] has Issued/Returned the Book: [book] at [date]." ,
                        "SMS" => "Dear [admin_name],
                        A Student: [student_name] has Issued/Returned the Book: [book] at [date]." ,
                        "Web" => "Dear [admin_name],
                        A Student: [student_name] has Issued/Returned the Book: [book] at [date]." ,
                        "App" => "Dear [admin_name],
                        A Student: [student_name] has Issued/Returned the Book: [book] at [date]." ,
                    ],
                ],

                'shortcode' => [
                    "Student" => "[student_name], [book], [date]",
                    "Parent" => "[parent_name], [book], [date]",
                    "Super admin" => "[admin_name], [student_name], [book], [date]"
                ]
            ],
            [
                'event' => 'Assign_Vehicle',
                'destination' => [
                    "Email" => 1, 
                    "SMS" => 1, 
                    "Web" => 1, 
                    "App" => 1
                ],
                'recipient' => [
                    "Student" => 1,
                    "Parent" => 1,
                    'Super admin' => 1,
                ],
                'subject' => [
                    "Student"=> "Assign Vehicle." ,
                    "Parent"=> "Assign Vehicle." ,
                    "Super admin"=> "Assign Vehicle.",
                ],

                'template' => [
                    "Student" => [
                        "Email" => "Dear [student_name],
                        Vehicle No: [vehicle_no] has been assigned to Route: [route] for transport purpose. For any query, please contact with admin." ,
                        "SMS" => "Dear [student_name],
                        Vehicle No: [vehicle_no] has been assigned to Route: [route] for transport purpose. For any query, please contact with admin." ,
                        "Web" => "Dear [student_name],
                        Vehicle No: [vehicle_no] has been assigned to Route: [route] for transport purpose. For any query, please contact with admin." ,
                        "App" => "Dear [student_name],
                        Vehicle No: [vehicle_no] has been assigned to Route: [route] for transport purpose. For any query, please contact with admin." ,
                    ],
                    "Parent"=> [
                        "Email" => "Dear [parent_name],
                        Vehicle No: [vehicle_no] has been assigned to Route: [route] for transport purpose. For any query, please contact with admin." ,
                        "SMS" => "Dear [parent_name],
                        Vehicle No: [vehicle_no] has been assigned to Route: [route] for transport purpose. For any query, please contact with admin." ,
                        "Web" => "Dear [parent_name],
                        Vehicle No: [vehicle_no] has been assigned to Route: [route] for transport purpose. For any query, please contact with admin." ,
                        "App" => "Dear [parent_name],
                        Vehicle No: [vehicle_no] has been assigned to Route: [route] for transport purpose. For any query, please contact with admin." ,
                    ],
                    "Super admin"=> [
                        "Email" => "Dear [admin_name],
                        Vehicle No: [vehicle_no] has been assigned to Route: [route] for transport purpose. For any query, please contact with admin." ,
                        "SMS" => "Dear [admin_name],
                        Vehicle No: [vehicle_no] has been assigned to Route: [route] for transport purpose. For any query, please contact with admin." ,
                        "Web" => "Dear [admin_name],
                        Vehicle No: [vehicle_no] has been assigned to Route: [route] for transport purpose. For any query, please contact with admin." ,
                        "App" => "Dear [admin_name],
                        Vehicle No: [vehicle_no] has been assigned to Route: [route] for transport purpose. For any query, please contact with admin." ,
                    ],
                ],

                'shortcode' => [
                    "Student" => "[student_name], [vehicle_no], [route]",
                    "Parent" => "[parent_name], [vehicle_no], [route]",
                    "Super admin" => "[admin_name], [vehicle_no], [route]"
                ]
            ],
            [
                'event' => 'Assign_Dormitory',
                'destination' => [
                    "Email" => 1, 
                    "SMS" => 1, 
                    "Web" => 1, 
                    "App" => 1
                ],
                'recipient' => [
                    "Student" => 1,
                    "Parent" => 1,
                ],
                'subject' => [
                    "Student"=> "Assign Dormitory." ,
                    "Parent"=> "Assign Dormitory." ,
                ],

                'template' => [
                    "Student" => [
                        "Email" => "Dear [student_name],
                        A Dormitory: [dormitory], Room [room] has been assigned for your accomodation. For any query, please contact with admin." ,
                        "SMS" => "Dear [student_name],
                        A Dormitory: [dormitory], Room [room] has been assigned for your accomodation. For any query, please contact with admin." ,
                        "Web" => "Dear [student_name],
                        A Dormitory: [dormitory], Room [room] has been assigned for your accomodation. For any query, please contact with admin." ,
                        "App" => "Dear [student_name],
                        A Dormitory: [dormitory], Room [room] has been assigned for your accomodation. For any query, please contact with admin." ,
                    ],
                    "Parent"=> [
                        "Email" => "Dear [parent_name],
                        A Dormitory: [dormitory], Room [room] has been assigned for your Child: [student_name]'s accomodation. For any query, please contact with admin." ,
                        "SMS" => "Dear [parent_name],
                        A Dormitory: [dormitory], Room [room] has been assigned for your Child: [student_name]'s accomodation. For any query, please contact with admin." ,
                        "Web" => "Dear [parent_name],
                        A Dormitory: [dormitory], Room [room] has been assigned for your Child: [student_name]'s accomodation. For any query, please contact with admin." ,
                        "App" => "Dear [parent_name],
                        A Dormitory: [dormitory], Room [room] has been assigned for your Child: [student_name]'s accomodation. For any query, please contact with admin." ,
                    ],
                ],

                'shortcode' => [
                    "Student" => "[student_name], [dormitory], [room]",
                    "Parent" => "[parent_name], [student_name], [dormitory], [room]"
                ]
            ],
            [
                'event' => 'Exam_Schedule',
                'destination' => [
                    "Email" => 1, 
                    "SMS" => 1, 
                    "Web" => 1, 
                    "App" => 1
                ],
                'recipient' => [
                    "Student" => 1,
                    "Parent" => 1,
                ],
                'subject' => [
                    "Student"=> "New Exam Schedule." ,
                    "Parent"=> "New Exam Schedule." ,
                ],

                'template' => [
                    "Student" => [
                        "Email" => "Dear [student_name],
                        You have a new Exam Schedule: [exam_schedule] for Subject: [subject]. For any query, please contact with admin." ,
                        "SMS" => "Dear [student_name],
                        You have a new Exam Schedule: [exam_schedule] for Subject: [subject]. For any query, please contact with admin." ,
                        "Web" => "Dear [student_name],
                        You have a new Exam Schedule: [exam_schedule] for Subject: [subject]. For any query, please contact with admin." ,
                        "App" => "Dear [student_name],
                        You have a new Exam Schedule: [exam_schedule] for Subject: [subject]. For any query, please contact with admin." ,
                    ],
                    "Parent"=> [
                        "Email" => "Dear [parent_name],
                        Your child [student_name] have a new Exam Schedule: [exam_schedule] for Subject: [subject]. For any query, please contact with admin." ,
                        "SMS" => "Dear [parent_name],
                        Your child [student_name] have a new Exam Schedule: [exam_schedule] for Subject: [subject]. For any query, please contact with admin." ,
                        "Web" => "Dear [parent_name],
                        Your child [student_name] have a new Exam Schedule: [exam_schedule] for Subject: [subject]. For any query, please contact with admin." ,
                        "App" => "Dear [parent_name],
                        Your child [student_name] have a new Exam Schedule: [exam_schedule] for Subject: [subject]. For any query, please contact with admin." ,
                    ],
                ],

                'shortcode' => [
                    "Student" => "[student_name], [exam_schedule], [subject]",
                    "Parent" => "[parent_name], [student_name], [exam_schedule], [subject]",
                ]
            ],
            [
                'event' => 'Exam_Attendance',
                'destination' => [
                    "Email" => 1, 
                    "SMS" => 1, 
                    "Web" => 1, 
                    "App" => 1
                ],
                'recipient' => [
                    "Student" => 1,
                    "Parent" => 1,
                ],
                'subject' => [
                    "Student"=> "Exam Attendance." ,
                    "Parent"=> "Exam Attendance." ,
                ],

                'template' => [
                    "Student" => [
                        "Email" => "Dear [student_name],
                        Your exam attendance for Subject: [subject] has been generated. For any query, please contact with admin." ,
                        "SMS" => "Dear [student_name],
                        Your exam attendance for Subject: [subject] has been generated. For any query, please contact with admin." ,
                        "Web" => "Dear [student_name],
                        Your exam attendance for Subject: [subject] has been generated. For any query, please contact with admin." ,
                        "App" => "Dear [student_name],
                        Your exam attendance for Subject: [subject] has been generated. For any query, please contact with admin." ,
                    ],
                    "Parent"=> [
                        "Email" => "Dear [parent_name],
                        Your child [student_name]'s exam attendance for Subject: [subject] has been generated. For any query, please contact with admin." ,
                        "SMS" => "Dear [parent_name],
                        Your child [student_name]'s exam attendance for Subject: [subject] has been generated. For any query, please contact with admin." ,
                        "Web" => "Dear [parent_name],
                        Your child [student_name]'s exam attendance for Subject: [subject] has been generated. For any query, please contact with admin." ,
                        "App" => "Dear [parent_name],
                        Your child [student_name]'s exam attendance for Subject: [subject] has been generated. For any query, please contact with admin." ,
                    ],
                ],

                'shortcode' => [
                    "Student" => "[student_name], [subject]",
                    "Parent" => "[parent_name], [student_name], [subject]"
                ],
            ],
            [
                'event' => 'Exam_Admit_Card',
                'destination' => [
                    "Email" => 1, 
                    "SMS" => 1, 
                    "Web" => 1, 
                    "App" => 1
                ],
                'recipient' => [
                    "Student" => 1,
                    "Parent" => 1,
                ],
                'subject' => [
                    "Student"=> "Exam Admit Card." ,
                    "Parent"=> "Exam Admit Card." ,
                ],

                'template' => [
                    "Student" => [
                        "Email" => "Dear [student_name],
                        Your admit card has been generated. For any query, please contact with admin." ,
                        "SMS" => "Dear [student_name],
                        Your admit card has been generated. For any query, please contact with admin." ,
                        "Web" => "Dear [student_name],
                        Your admit card has been generated. For any query, please contact with admin." ,
                        "App" => "Dear [student_name],
                        Your admit card has been generated. For any query, please contact with admin." ,
                    ],
                    "Parent"=> [
                        "Email" => "Dear [parent_name],
                        Your child [student_name]'s admit card has been generated. For any query, please contact with admin." ,
                        "SMS" => "Dear [parent_name],
                        Your child [student_name]'s admit card has been generated. For any query, please contact with admin." ,
                        "Web" => "Dear [parent_name],
                        Your child [student_name]'s admit card has been generated. For any query, please contact with admin." ,
                        "App" => "Dear [parent_name],
                        Your child [student_name]'s admit card has been generated. For any query, please contact with admin." ,
                    ],
                ],

                'shortcode' => [
                    "Student" => "[student_name]",
                    "Parent" => "[parent_name], [student_name]"
                ]
            ],
            [
                'event' => 'Exam_Seat_Plan',
                'destination' => [
                    "Email" => 1, 
                    "SMS" => 1, 
                    "Web" => 1, 
                    "App" => 1
                ],
                'recipient' => [
                    "Student" => 1,
                    "Parent" => 1,
                ],
                'subject' => [
                    "Student"=> "Exam Seat Plan." ,
                    "Parent"=> "Exam Seat Plan." ,
                ],

                'template' => [
                    "Student" => [
                        "Email" => "Dear [student_name],
                        Your seat plan has been generated. For any query, please contact with admin." ,
                        "SMS" => "Dear [student_name],
                        Your seat plan has been generated. For any query, please contact with admin." ,
                        "Web" => "Dear [student_name],
                        Your seat plan has been generated. For any query, please contact with admin." ,
                        "App" => "Dear [student_name],
                        Your seat plan has been generated. For any query, please contact with admin." ,
                    ],
                    "Parent"=> [
                        "Email" => "Dear [parent_name],
                        Your child [student_name]'s seat plan has been generated. For any query, please contact with admin." ,
                        "SMS" => "Dear [parent_name],
                        Your child [student_name]'s seat plan has been generated. For any query, please contact with admin." ,
                        "Web" => "Dear [parent_name],
                        Your child [student_name]'s seat plan has been generated. For any query, please contact with admin." ,
                        "App" => "Dear [parent_name],
                        Your child [student_name]'s seat plan has been generated. For any query, please contact with admin." ,
                    ],
                ],

                'shortcode' => [
                    "Student" => "[student_name]",
                    "Parent" => "[parent_name], [student_name]"
                ]
            ],
            [
                'event' => 'Online_Exam_Publish',
                'destination' => [
                    "Email" => 1, 
                    "SMS" => 1, 
                    "Web" => 1, 
                    "App" => 1
                ],
                'recipient' => [
                    "Student" => 1,
                    "Parent" => 1,
                ],
                'subject' => [
                    "Student"=> "Online Exam Published." ,
                    "Parent"=> "Online Exam Published." ,
                ],

                'template' => [
                    "Student" => [
                        "Email" => "Dear [student_name],
                        A new Online Exam has been published for Subject: [subject]. For any query, please contact with admin." ,
                        "SMS" => "Dear [student_name],
                        A new Online Exam has been published for Subject: [subject]. For any query, please contact with admin." ,
                        "Web" => "Dear [student_name],
                        A new Online Exam has been published for Subject: [subject]. For any query, please contact with admin." ,
                        "App" => "Dear [student_name],
                        A new Online Exam has been published for Subject: [subject]. For any query, please contact with admin." ,
                    ],
                    "Parent"=> [
                        "Email" => "Dear [parent_name],
                        Your child [student_name] has a new published Online Exam for Subject: [subject]. For any query, please contact with admin." ,
                        "SMS" => "Dear [parent_name],
                        Your child [student_name] has a new published Online Exam for Subject: [subject]. For any query, please contact with admin." ,
                        "Web" => "Dear [parent_name],
                        Your child [student_name] has a new published Online Exam for Subject: [subject]. For any query, please contact with admin." ,
                        "App" => "Dear [parent_name],
                        Your child [student_name] has a new published Online Exam for Subject: [subject]. For any query, please contact with admin." ,
                    ],
                ],

                'shortcode' => [
                    "Student" => "[student_name], [subject]",
                    "Parent" => "[parent_name], [student_name], [subject]"
                ]
            ],
            [
                'event' => 'Online_Exam_Result',
                'destination' => [
                    "Email" => 1, 
                    "SMS" => 1, 
                    "Web" => 1, 
                    "App" => 1
                ],
                'recipient' => [
                    "Student" => 1,
                    "Parent" => 1,
                ],
                'subject' => [
                    "Student"=> "Online Exam Result." ,
                    "Parent"=> "Online Exam Result." ,
                ],

                'template' => [
                    "Student" => [
                        "Email" => "Dear [student_name],
                        The Online Exam: [online_exam]'s result has been published for Subject: [subject]. For any query, please contact with admin." ,
                        "SMS" => "Dear [student_name],
                        The Online Exam: [online_exam]'s result has been published for Subject: [subject]. For any query, please contact with admin." ,
                        "Web" => "Dear [student_name],
                        The Online Exam: [online_exam]'s result has been published for Subject: [subject]. For any query, please contact with admin." ,
                        "App" => "Dear [student_name],
                        The Online Exam: [online_exam]'s result has been published for Subject: [subject]. For any query, please contact with admin." ,
                    ],
                    "Parent"=> [
                        "Email" => "Dear [parent_name],
                        Your child [student_name]'s Online Exam: [online_exam] result has been published for Subject: [subject]. For any query, please contact with admin." ,
                        "SMS" => "Dear [parent_name],
                        Your child [student_name]'s Online Exam: [online_exam] result has been published for Subject: [subject]. For any query, please contact with admin." ,
                        "Web" => "Dear [parent_name],
                        Your child [student_name]'s Online Exam: [online_exam] result has been published for Subject: [subject]. For any query, please contact with admin." ,
                        "App" => "Dear [parent_name],
                        Your child [student_name]'s Online Exam: [online_exam] result has been published for Subject: [subject]. For any query, please contact with admin." ,
                    ],
                ],

                'shortcode' => [
                    "Student" => "[student_name], [online_exam], [subject]",
                    "Parent" => "[parent_name], [student_name], [online_exam], [subject]"
                ]
            ],
            [
                'event' => 'Staff_Attendance',
                'destination' => [
                    "Email" => 1, 
                    "SMS" => 1, 
                    "Web" => 1, 
                    "App" => 1
                ],
                'recipient' => [
                    'Super admin' => 1,
                    'Teacher' => 1
                ],
                'subject' => [
                    "Super admin"=> "Staff Attendance.",
                    "Teacher" => "Staff Attendance."
                ],

                'template' => [
                    "Super admin"=> [
                        "Email" => "Dear [admin_name],
                        The Staff Attendance for today has been generated." ,
                        "SMS" => "Dear [admin_name],
                        The Staff Attendance for today has been generated." ,
                        "Web" => "Dear [admin_name],
                        The Staff Attendance for today has been generated." ,
                        "App" => "Dear [admin_name],
                        The Staff Attendance for today has been generated." ,
                    ],
                    "Teacher"=> [
                        "Email" => "Dear [teacher_name],
                        The Staff Attendance for today has been generated. For any query, please contact with admin." ,
                        "SMS" => "Dear [teacher_name],
                        The Staff Attendance for today has been generated. For any query, please contact with admin." ,
                        "Web" => "Dear [teacher_name],
                        The Staff Attendance for today has been generated. For any query, please contact with admin." ,
                        "App" => "Dear [teacher_name],
                        The Staff Attendance for today has been generated. For any query, please contact with admin." ,
                    ],
                ],

                'shortcode' => [
                    "Super admin" => "[admin_name]",
                    "Teacher" => "[teacher_name]",
                ]
            ],
            [
                'event' => 'Staff_Payroll',
                'destination' => [
                    "Email" => 1, 
                    "SMS" => 1, 
                    "Web" => 1, 
                    "App" => 1
                ],
                'recipient' => [
                    'Super admin' => 1,
                    'Teacher' => 1
                ],
                'subject' => [
                    "Super admin"=> "Staff Payroll.",
                    "Teacher" => "Staff Payroll."
                ],

                'template' => [
                    "Super admin"=> [
                        "Email" => "Dear [admin_name],
                        The Staff Payroll for this month has been generated." ,
                        "SMS" => "Dear [admin_name],
                        The Staff Payroll for this month has been generated." ,
                        "Web" => "Dear [admin_name],
                        The Staff Payroll for this month has been generated." ,
                        "App" => "Dear [admin_name],
                        The Staff Payroll for this month has been generated." ,
                    ],
                    "Teacher"=> [
                        "Email" => "Dear [teacher_name],
                        The Staff Payroll for this month has been generated. For any query, please contact with admin." ,
                        "SMS" => "Dear [teacher_name],
                        The Staff Payroll for this month has been generated. For any query, please contact with admin." ,
                        "Web" => "Dear [teacher_name],
                        The Staff Payroll for this month has been generated. For any query, please contact with admin." ,
                        "App" => "Dear [teacher_name],
                        The Staff Payroll for this month has been generated. For any query, please contact with admin." ,
                    ],
                ],

                'shortcode' => [
                    "Super admin" => "[admin_name]",
                    "Teacher" => "[teacher_name]"
                ]
            ],
            [
                'event' => 'Leave_Apply',
                'destination' => [
                    "Email" => 1, 
                    "SMS" => 1, 
                    "Web" => 1, 
                    "App" => 1
                ],
                'recipient' => [
                    "Student" => 1,
                    "Parent" => 1,
                    'Super admin' => 1,
                    'Teacher' => 1
                ],
                'subject' => [
                    "Student"=> "Leave Apply." ,
                    "Parent"=> "Leave Apply." ,
                    "Super admin"=> "Leave Apply.",
                    "Teacher" => "Leave Apply."
                ],

                'template' => [
                    "Student" => [
                        "Email" => "Dear [student_name],
                        You have applied for a leave from [from_date] to [to_date]. For any query, please contact with admin." ,
                        "SMS" => "Dear [student_name],
                        You have applied for a leave from [from_date] to [to_date]. For any query, please contact with admin." ,
                        "Web" => "Dear [student_name],
                        You have applied for a leave from [from_date] to [to_date]. For any query, please contact with admin." ,
                        "App" => "Dear [student_name],
                        You have applied for a leave from [from_date] to [to_date]. For any query, please contact with admin." ,
                    ],
                    "Parent"=> [
                        "Email" => "Dear [parent_name],
                        You have applied for a leave for your child [student_name] from [from_date] to [to_date]. For any query, please contact with admin." ,
                        "SMS" => "Dear [parent_name],
                        You have applied for a leave for your child [student_name] from [from_date] to [to_date]. For any query, please contact with admin." ,
                        "Web" => "Dear [parent_name],
                        You have applied for a leave for your child [student_name] from [from_date] to [to_date]. For any query, please contact with admin." ,
                        "App" => "Dear [parent_name],
                        You have applied for a leave for your child [student_name] from [from_date] to [to_date]. For any query, please contact with admin." ,
                    ],
                    "Super admin"=> [
                        "Email" => "Dear [admin_name],
                        [name] has applied for a leave from [from_date] to [to_date]." ,
                        "SMS" => "Dear [admin_name],
                        [name] has applied for a leave from [from_date] to [to_date]." ,
                        "Web" => "Dear [admin_name],
                        [name] has applied for a leave from [from_date] to [to_date]." ,
                        "App" => "Dear [admin_name],
                        [name] has applied for a leave from [from_date] to [to_date]." ,
                    ],
                    "Teacher"=> [
                        "Email" => "Dear [teacher_name],
                        You have applied for a leave from [from_date] to [to_date]. For any query, please contact with admin." ,
                        "SMS" => "Dear [teacher_name],
                        You have applied for a leave from [from_date] to [to_date]. For any query, please contact with admin." ,
                        "Web" => "Dear [teacher_name],
                        You have applied for a leave from [from_date] to [to_date]. For any query, please contact with admin." ,
                        "App" => "Dear [teacher_name],
                        You have applied for a leave from [from_date] to [to_date]. For any query, please contact with admin." ,
                    ],
                ],

                'shortcode' => [
                    "Student" => "[student_name], [from_date], [to_date]",
                    "Parent" => "[parent_name], [student_name], [from_date], [to_date]",
                    "Super admin" => "[admin_name], [name], [from_date], [to_date]",
                    "Teacher" => "[teacher_name], [from_date], [to_date]"
                ]
            ],
            [
                'event' => 'Leave_Approved',
                'destination' => [
                    "Email" => 1, 
                    "SMS" => 1, 
                    "Web" => 1, 
                    "App" => 1
                ],
                'recipient' => [
                    "Student" => 1,
                    "Parent" => 1,
                    'Super admin' => 1,
                    'Teacher' => 1
                ],
                'subject' => [
                    "Student"=> "Leave Approved." ,
                    "Parent"=> "Leave Approved." ,
                    "Super admin"=> "Leave Approved.",
                    "Teacher" => "Leave Approved."
                ],

                'template' => [
                    "Student" => [
                        "Email" => "Dear [student_name],
                        Your leave from [from_date] to [to_date] has been approved. For any query, please contact with admin." ,
                        "SMS" => "Dear [student_name],
                        Your leave from [from_date] to [to_date] has been approved. For any query, please contact with admin." ,
                        "Web" => "Dear [student_name],
                        Your leave from [from_date] to [to_date] has been approved. For any query, please contact with admin." ,
                        "App" => "Dear [student_name],
                        Your leave from [from_date] to [to_date] has been approved. For any query, please contact with admin." ,
                    ],
                    "Parent"=> [
                        "Email" => "Dear [parent_name],
                        Your child [student_name]'s leave from [from_date] to [to_date] has been approved. For any query, please contact with admin." ,
                        "SMS" => "Dear [parent_name],
                        Your child [student_name]'s leave from [from_date] to [to_date] has been approved. For any query, please contact with admin." ,
                        "Web" => "Dear [parent_name],
                        Your child [student_name]'s leave from [from_date] to [to_date] has been approved. For any query, please contact with admin." ,
                        "App" => "Dear [parent_name],
                        Your child [student_name]'s leave from [from_date] to [to_date] has been approved. For any query, please contact with admin." ,
                    ],
                    "Super admin"=> [
                        "Email" => "Dear [admin_name],
                        [name]'s leave request from [from_date] to [to_date] has been approved." ,
                        "SMS" => "Dear [admin_name],
                        [name]'s leave request from [from_date] to [to_date] has been approved." ,
                        "Web" => "Dear [admin_name],
                        [name]'s leave request from [from_date] to [to_date] has been approved." ,
                        "App" => "Dear [admin_name],
                        [name]'s leave request from [from_date] to [to_date] has been approved." ,
                    ],
                    "Teacher"=> [
                        "Email" => "Dear [teacher_name],
                        Your leave from [from_date] to [to_date] has been approved. For any query, please contact with admin." ,
                        "SMS" => "Dear [teacher_name],
                        Your leave from [from_date] to [to_date] has been approved. For any query, please contact with admin." ,
                        "Web" => "Dear [teacher_name],
                        Your leave from [from_date] to [to_date] has been approved. For any query, please contact with admin." ,
                        "App" => "Dear [teacher_name],
                        Your leave from [from_date] to [to_date] has been approved. For any query, please contact with admin." ,
                    ],
                ],

                'shortcode' => [
                    "Student" => "[student_name], [from_date], [to_date]",
                    "Parent" => "[parent_name], [student_name], [from_date], [to_date]",
                    "Super admin" => "[admin_name], [name], [from_date], [to_date]",
                    "Teacher" => "[teacher_name], [from_date], [to_date]"
                ]
            ],
            [
                'event' => 'Leave_Declined',
                'destination' => [
                    "Email" => 1, 
                    "SMS" => 1, 
                    "Web" => 1, 
                    "App" => 1
                ],
                'recipient' => [
                    "Student" => 1,
                    "Parent" => 1,
                    'Super admin' => 1,
                    'Teacher' => 1
                ],
                'subject' => [
                    "Student"=> "Leave Declined." ,
                    "Parent"=> "Leave Declined." ,
                    "Super admin"=> "Leave Declined.",
                    "Teacher" => "Leave Declined."
                ],

                'template' => [
                    "Student" => [
                        "Email" => "Dear [student_name],
                        Your leave from [from_date] to [to_date] has been declined. For any query, please contact with admin." ,
                        "SMS" => "Dear [student_name],
                        Your leave from [from_date] to [to_date] has been declined. For any query, please contact with admin." ,
                        "Web" => "Dear [student_name],
                        Your leave from [from_date] to [to_date] has been declined. For any query, please contact with admin." ,
                        "App" => "Dear [student_name],
                        Your leave from [from_date] to [to_date] has been declined. For any query, please contact with admin." ,
                    ],
                    "Parent"=> [
                        "Email" => "Dear [parent_name],
                        Your child [student_name]'s leave from [from_date] to [to_date] has been declined. For any query, please contact with admin." ,
                        "SMS" => "Dear [parent_name],
                        Your child [student_name]'s leave from [from_date] to [to_date] has been declined. For any query, please contact with admin." ,
                        "Web" => "Dear [parent_name],
                        Your child [student_name]'s leave from [from_date] to [to_date] has been declined. For any query, please contact with admin." ,
                        "App" => "Dear [parent_name],
                        Your child [student_name]'s leave from [from_date] to [to_date] has been declined. For any query, please contact with admin." ,
                    ],
                    "Super admin"=> [
                        "Email" => "Dear [admin_name],
                        [name]'s leave request from [from_date] to [to_date] has been declined." ,
                        "SMS" => "Dear [admin_name],
                        [name]'s leave request from [from_date] to [to_date] has been declined." ,
                        "Web" => "Dear [admin_name],
                        [name]'s leave request from [from_date] to [to_date] has been declined." ,
                        "App" => "Dear [admin_name],
                        [name]'s leave request from [from_date] to [to_date] has been declined." ,
                    ],
                    "Teacher"=> [
                        "Email" => "Dear [teacher_name],
                        Your leave from [from_date] to [to_date] has been declined. For any query, please contact with admin." ,
                        "SMS" => "Dear [teacher_name],
                        Your leave from [from_date] to [to_date] has been declined. For any query, please contact with admin." ,
                        "Web" => "Dear [teacher_name],
                        Your leave from [from_date] to [to_date] has been declined. For any query, please contact with admin." ,
                        "App" => "Dear [teacher_name],
                        Your leave from [from_date] to [to_date] has been declined. For any query, please contact with admin." ,
                    ],
                ],

                'shortcode' => [
                    "Student" => "[student_name], [from_date], [to_date]",
                    "Parent" => "[parent_name], [student_name], [from_date], [to_date]",
                    "Super admin" => "[admin_name], [name], [from_date], [to_date]",
                    "Teacher" => "[teacher_name], [from_date], [to_date]"
                ]
            ],
            [
                'event' => 'Approve_Deposit',
                'destination' => [
                    "Email" => 1, 
                    "SMS" => 1, 
                    "Web" => 1, 
                    "App" => 1
                ],
                'recipient' => [
                    "Student" => 1,
                    "Parent" => 1,
                    'Super admin' => 1,
                ],
                'subject' => [
                    "Student"=> "Approve Deposit." ,
                    "Parent"=> "Approve Deposit." ,
                    "Super admin"=> "Approve Deposit.",
                ],

                'template' => [
                    "Student" => [
                        "Email" => "Dear [student_name],
                        Your deposit request for Amount: [amount] has been approved. For any query, please contact with admin." ,
                        "SMS" => "Dear [student_name],
                        Your deposit request for Amount: [amount] has been approved. For any query, please contact with admin." ,
                        "Web" => "Dear [student_name],
                        Your deposit request for Amount: [amount] has been approved. For any query, please contact with admin." ,
                        "App" => "Dear [student_name],
                        Your deposit request for Amount: [amount] has been approved. For any query, please contact with admin." ,
                    ],
                    "Parent"=> [
                        "Email" => "Dear [parent_name],
                        Your deposit request for Amount: [amount] has been approved. For any query, please contact with admin." ,
                        "SMS" => "Dear [parent_name],
                        Your deposit request for Amount: [amount] has been approved. For any query, please contact with admin." ,
                        "Web" => "Dear [parent_name],
                        Your deposit request for Amount: [amount] has been approved. For any query, please contact with admin." ,
                        "App" => "Dear [parent_name],
                        Your deposit request for Amount: [amount] has been approved. For any query, please contact with admin." ,
                    ],
                    "Super admin"=> [
                        "Email" => "Dear [admin_name],
                        A deposit request from [student_name]/[parent_name] for Amount: [amount] has been approved." ,
                        "SMS" => "Dear [admin_name],
                        A deposit request from [student_name]/[parent_name] for Amount: [amount] has been approved." ,
                        "Web" => "Dear [admin_name],
                        A deposit request from [student_name]/[parent_name] for Amount: [amount] has been approved." ,
                        "App" => "Dear [admin_name],
                        A deposit request from [student_name]/[parent_name] for Amount: [amount] has been approved." ,
                    ],
                ],

                'shortcode' => [
                    "Student" => "[student_name], [amount]",
                    "Parent" => "[parent_name], [student_name], [amount]",
                    "Super admin" => "[admin_name], [parent_name], [student_name], [amount]"
                ]
            ],
            [
                'event' => 'Reject_Deposit',
                'destination' => [
                    "Email" => 1, 
                    "SMS" => 1, 
                    "Web" => 1, 
                    "App" => 1
                ],
                'recipient' => [
                    "Student" => 1,
                    "Parent" => 1,
                    'Super admin' => 1,
                ],
                'subject' => [
                    "Student"=> "Reject Deposit." ,
                    "Parent"=> "Reject Deposit." ,
                    "Super admin"=> "Reject Deposit.",
                ],

                'template' => [
                    "Student" => [
                        "Email" => "Dear [student_name],
                        Your deposit request for Amount: [amount] has been rejected. For any query, please contact with admin." ,
                        "SMS" => "Dear [student_name],
                        Your deposit request for Amount: [amount] has been rejected. For any query, please contact with admin." ,
                        "Web" => "Dear [student_name],
                        Your deposit request for Amount: [amount] has been rejected. For any query, please contact with admin." ,
                        "App" => "Dear [student_name],
                        Your deposit request for Amount: [amount] has been rejected. For any query, please contact with admin." ,
                    ],
                    "Parent"=> [
                        "Email" => "Dear [parent_name],
                        Your deposit request for Amount: [amount] has been rejected. For any query, please contact with admin." ,
                        "SMS" => "Dear [parent_name],
                        Your deposit request for Amount: [amount] has been rejected. For any query, please contact with admin." ,
                        "Web" => "Dear [parent_name],
                        Your deposit request for Amount: [amount] has been rejected. For any query, please contact with admin." ,
                        "App" => "Dear [parent_name],
                        Your deposit request for Amount: [amount] has been rejected. For any query, please contact with admin." ,
                    ],
                    "Super admin"=> [
                        "Email" => "Dear [admin_name],
                        A deposit request from [student_name]/[parent_name] for Amount: [amount] has been rejected." ,
                        "SMS" => "Dear [admin_name],
                        A deposit request from [student_name]/[parent_name] for Amount: [amount] has been rejected." ,
                        "Web" => "Dear [admin_name],
                        A deposit request from [student_name]/[parent_name] for Amount: [amount] has been rejected." ,
                        "App" => "Dear [admin_name],
                        A deposit request from [student_name]/[parent_name] for Amount: [amount] has been rejected." ,
                    ],
                ],

                'shortcode' => [
                    "Student" => "[student_name], [amount]",
                    "Parent" => "[parent_name], [student_name], [amount]",
                    "Super admin" => "[admin_name], [parent_name], [student_name], [amount]"
                ]
            ],
            [
                'event' => 'Wallet_Add',
                'destination' => [
                    "Email" => 1, 
                    "SMS" => 1, 
                    "Web" => 1, 
                    "App" => 1
                ],
                'recipient' => [
                    "Student" => 1,
                    "Parent" => 1,
                    'Super admin' => 1,
                ],
                'subject' => [
                    "Student"=> "Wallet Add." ,
                    "Parent"=> "Wallet Add." ,
                    "Super admin"=> "Wallet Add.",
                ],

                'template' => [
                    "Student" => [
                        "Email" => "Dear [student_name],
                        Your wallet transaction for Amount: [amount] was successful. For any query, please contact with admin." ,
                        "SMS" => "Dear [student_name],
                        Your wallet transaction for Amount: [amount] was successful. For any query, please contact with admin." ,
                        "Web" => "Dear [student_name],
                        Your wallet transaction for Amount: [amount] was successful. For any query, please contact with admin." ,
                        "App" => "Dear [student_name],
                        Your wallet transaction for Amount: [amount] was successful. For any query, please contact with admin." ,
                    ],
                    "Parent"=> [
                        "Email" => "Dear [parent_name],
                        Your wallet transaction for Amount: [amount] was successful. For any query, please contact with admin." ,
                        "SMS" => "Dear [parent_name],
                        Your wallet transaction for Amount: [amount] was successful. For any query, please contact with admin." ,
                        "Web" => "Dear [parent_name],
                        Your wallet transaction for Amount: [amount] was successful. For any query, please contact with admin." ,
                        "App" => "Dear [parent_name],
                        Your wallet transaction for Amount: [amount] was successful. For any query, please contact with admin." ,
                    ],
                    "Super admin"=> [
                        "Email" => "Dear [admin_name],
                        A wallet transaction from [student_name]/[parent_name]'s account for Amount: [amount] was complete." ,
                        "SMS" => "Dear [admin_name],
                        A wallet transaction from [student_name]/[parent_name]'s account for Amount: [amount] was complete." ,
                        "Web" => "Dear [admin_name],
                        A wallet transaction from [student_name]/[parent_name]'s account for Amount: [amount] was complete." ,
                        "App" => "Dear [admin_name],
                        A wallet transaction from [student_name]/[parent_name]'s account for Amount: [amount] was complete." ,
                    ],
                ],

                'shortcode' => [
                    "Student" => "[student_name], [amount]",
                    "Parent" => "[parent_name], [student_name], [amount]",
                    "Super admin" => "[admin_name], [parent_name], [student_name], [amount]"
                ]
            ],
            [
                'event' => 'Refund_Deposit',
                'destination' => [
                    "Email" => 1, 
                    "SMS" => 1, 
                    "Web" => 1, 
                    "App" => 1
                ],
                'recipient' => [
                    "Student" => 1,
                    "Parent" => 1,
                    'Super admin' => 1,
                ],
                'subject' => [
                    "Student"=> "Refund Deposit." ,
                    "Parent"=> "Refund Deposit." ,
                    "Super admin"=> "Refund Deposit.",
                ],

                'template' => [
                    "Student" => [
                        "Email" => "Dear [student_name],
                        Your deposit refund request for Amount: [amount] has been accepted. For any query, please contact with admin." ,
                        "SMS" => "Dear [student_name],
                        Your deposit refund request for Amount: [amount] has been accepted. For any query, please contact with admin." ,
                        "Web" => "Dear [student_name],
                        Your deposit refund request for Amount: [amount] has been accepted. For any query, please contact with admin." ,
                        "App" => "Dear [student_name],
                        Your deposit refund request for Amount: [amount] has been accepted. For any query, please contact with admin." ,
                    ],
                    "Parent"=> [
                        "Email" => "Dear [parent_name],
                        Your deposit refund request for Amount: [amount] has been accepted. For any query, please contact with admin." ,
                        "SMS" => "Dear [parent_name],
                        Your deposit refund request for Amount: [amount] has been accepted. For any query, please contact with admin." ,
                        "Web" => "Dear [parent_name],
                        Your deposit refund request for Amount: [amount] has been accepted. For any query, please contact with admin." ,
                        "App" => "Dear [parent_name],
                        Your deposit refund request for Amount: [amount] has been accepted. For any query, please contact with admin." ,
                    ],
                    "Super admin"=> [
                        "Email" => "Dear [admin_name],
                        A deposit refund from [student_name]/[parent_name] for Amount: [amount] has been requested." ,
                        "SMS" => "Dear [admin_name],
                        A deposit refund from [student_name]/[parent_name] for Amount: [amount] has been requested." ,
                        "Web" => "Dear [admin_name],
                        A deposit refund from [student_name]/[parent_name] for Amount: [amount] has been requested." ,
                        "App" => "Dear [admin_name],
                        A deposit refund from [student_name]/[parent_name] for Amount: [amount] has been requested." ,
                    ],
                ],

                'shortcode' => [
                    "Student" => "[student_name], [amount]",
                    "Parent" => "[parent_name], [student_name], [amount]",
                    "Super admin" => "[admin_name], [parent_name], [student_name], [amount]"
                ]
            ],
            [
                'event' => 'Fund_Transfer',
                'destination' => [
                    "Email" => 1, 
                    "SMS" => 1, 
                    "Web" => 1, 
                    "App" => 1
                ],
                'recipient' => [
                    'Super admin' => 1,
                ],
                'subject' => [
                    "Super admin"=> "Fund Transfer.",
                ],

                'template' => [
                    "Super admin"=> [
                        "Email" => "Dear [admin_name],
                        A fund transfer of Amount: [amount] was successful." ,
                        "SMS" => "Dear [admin_name],
                        A fund transfer of Amount: [amount] was successful." ,
                        "Web" => "Dear [admin_name],
                        A fund transfer of Amount: [amount] was successful." ,
                        "App" => "Dear [admin_name],
                        A fund transfer of Amount: [amount] was successful." ,
                    ],
                ],

                'shortcode' => [
                    "Super admin" => "[admin_name], [amount]"
                ]
            ],
            [
                'event' => 'Item_Recieved',
                'destination' => [
                    "Email" => 1, 
                    "SMS" => 1, 
                    "Web" => 1, 
                    "App" => 1
                ],
                'recipient' => [
                    'Super admin' => 1,
                ],
                'subject' => [
                    "Super admin"=> "Item Recieved.",
                ],

                'template' => [
                    "Super admin"=> [
                        "Email" => "Dear [admin_name],
                        An Item: [item] was recieved grand total: [grand_total], total: [total_paid], item: [item], qty: [quantity]" ,
                        "SMS" => "Dear [admin_name],
                        An Item: [item] was recieved grand total:[grand_total], total: [total_paid], item: [item], qty: [quantity]" ,
                        "Web" => "Dear [admin_name],
                        An Item: [item] was recieved  grand total: [grand_total], total: [total_paid], item: [item], qty: [quantity]" ,
                        "App" => "Dear [admin_name],
                        An Item: [item] was recieved grand total: [grand_total], total: [total_paid], item: [item], qty: [quantity]" ,
                    ],
                ],

                'shortcode' => [
                    "Super admin" => "[admin_name], [grand_total], [total_paid], [item], [quantity]",
                ]
            ],
            [
                'event' => 'Item_sell',
                'destination' => [
                    "Email" => 1, 
                    "SMS" => 1, 
                    "Web" => 1, 
                    "App" => 1
                ],
                'recipient' => [
                    'Super admin' => 1,
                ],
                'subject' => [
                    "Super admin"=> "Item Sold.",
                ],

                'template' => [
                    "Super admin"=> [
                        "Email" => "Dear [admin_name],
                        An Item: [item] was sold total: [total_paid], qty: [quantity]" ,
                        "SMS" => "Dear [admin_name],
                        An Item: [item] was sold total: [total_paid], qty: [quantity]" ,
                        "Web" => "Dear [admin_name],
                        An Item: [item] was sold total: [total_paid], qty: [quantity]" ,
                        "App" => "Dear [admin_name],
                        An Item: [item] was sold total: [total_paid], qty: [quantity]" ,
                    ],
                ],

                'shortcode' => [
                    "Super admin" => "[admin_name], [item], [total_paid], [quantity]",
                ]
            ],
            [
                'event' => 'Notice',
                'destination' => [
                    "Email" => 1, 
                    "SMS" => 1, 
                    "Web" => 1, 
                    "App" => 1
                ],
                'recipient' => [
                    "Student" => 1,
                    "Parent" => 1,
                    'Super admin' => 1,
                    'Teacher' => 1
                ],
                'subject' => [
                    "Student"=> "New Notice." ,
                    "Parent"=> "New Notice." ,
                    "Super admin"=> "New Notice.",
                    "Teacher" => "New Notice."
                ],

                'template' => [
                    "Student" => [
                        "Email" => "Dear [student_name],
                        You have a new Notice: [notice]. For any query, please contact with admin." ,
                        "SMS" => "Dear [student_name],
                        You have a new Notice: [notice]. For any query, please contact with admin." ,
                        "Web" => "Dear [student_name],
                        You have a new Notice: [notice]. For any query, please contact with admin." ,
                        "App" => "Dear [student_name],
                        You have a new Notice: [notice]. For any query, please contact with admin." ,
                    ],
                    "Parent"=> [
                        "Email" => "Dear [parent_name],
                        You have a new Notice: [notice]. For any query, please contact with admin." ,
                        "SMS" => "Dear [parent_name],
                        You have a new Notice: [notice]. For any query, please contact with admin." ,
                        "Web" => "Dear [parent_name],
                        You have a new Notice: [notice]. For any query, please contact with admin." ,
                        "App" => "Dear [parent_name],
                        You have a new Notice: [notice]. For any query, please contact with admin." ,
                    ],
                    "Super admin"=> [
                        "Email" => "Dear [admin_name],
                        You have a new Notice: [notice]." ,
                        "SMS" => "Dear [admin_name],
                        You have a new Notice: [notice]." ,
                        "Web" => "Dear [admin_name],
                        You have a new Notice: [notice]." ,
                        "App" => "Dear [admin_name],
                        You have a new Notice: [notice]." ,
                    ],
                    "Teacher"=> [
                        "Email" => "Dear [teacher_name],
                        You have a new Notice: [notice]. For any query, please contact with admin." ,
                        "SMS" => "Dear [teacher_name],
                        You have a new Notice: [notice]. For any query, please contact with admin." ,
                        "Web" => "Dear [teacher_name],
                        You have a new Notice: [notice]. For any query, please contact with admin." ,
                        "App" => "Dear [teacher_name],
                        You have a new Notice: [notice]. For any query, please contact with admin." ,
                    ],
                ],
                'shortcode' => [
                    "Student" => "[student_name], [notice]",
                    "Parent" => "[parent_name], [notice]",
                    "Super admin" => "[admin_name], [notice]",
                    "Teacher" => "[teacher_name], [notice]"
                ]
            ],
            [
                'event' => 'Event',
                'destination' => [
                    "Email" => 1, 
                    "SMS" => 1, 
                    "Web" => 1, 
                    "App" => 1
                ],
                'recipient' => [
                    "Student" => 1,
                    "Parent" => 1,
                    'Super admin' => 1,
                    'Teacher' => 1
                ],
                'subject' => [
                    "Student"=> "New Event." ,
                    "Parent"=> "New Event." ,
                    "Super admin"=> "New Event.",
                    "Teacher" => "New Event."
                ],

                'template' => [
                    "Student" => [
                        "Email" => "Dear [student_name],
                        You have a new Event: [event]. For any query, please contact with admin." ,
                        "SMS" => "Dear [student_name],
                        You have a new Event: [event]. For any query, please contact with admin." ,
                        "Web" => "Dear [student_name],
                        You have a new Event: [event]. For any query, please contact with admin." ,
                        "App" => "Dear [student_name],
                        You have a new Event: [event]. For any query, please contact with admin." ,
                    ],
                    "Parent"=> [
                        "Email" => "Dear [parent_name],
                        You have a new Event: [event]. For any query, please contact with admin." ,
                        "SMS" => "Dear [parent_name],
                        You have a new Event: [event]. For any query, please contact with admin." ,
                        "Web" => "Dear [parent_name],
                        You have a new Event: [event]. For any query, please contact with admin." ,
                        "App" => "Dear [parent_name],
                        You have a new Event: [event]. For any query, please contact with admin." ,
                    ],
                    "Super admin"=> [
                        "Email" => "Dear [admin_name],
                        You have a new Event: [event]." ,
                        "SMS" => "Dear [admin_name],
                        You have a new Event: [event]." ,
                        "Web" => "Dear [admin_name],
                        You have a new Event: [event]." ,
                        "App" => "Dear [admin_name],
                        You have a new Event: [event]." ,
                    ],
                    "Teacher"=> [
                        "Email" => "Dear [teacher_name],
                        You have a new Event: [event]. For any query, please contact with admin." ,
                        "SMS" => "Dear [teacher_name],
                        You have a new Event: [event]. For any query, please contact with admin." ,
                        "Web" => "Dear [teacher_name],
                        You have a new Event: [event]. For any query, please contact with admin." ,
                        "App" => "Dear [teacher_name],
                        You have a new Event: [event]. For any query, please contact with admin." ,
                    ],
                ],

                'shortcode' => [
                    "Student" => "[student_name], [event]",
                    "Parent" => "[parent_name], [event]",
                    "Super admin" => "[admin_name], [event]",
                    "Teacher" => "[teacher_name], [event]"
                ]
            ],
        ];

        foreach($all_events as $event){
        $newEvent = new SmNotificationSetting();
            $newEvent->event = $event['event'];
            $newEvent->destination = $event['destination'];
            $newEvent->recipient = $event['recipient'];
            $newEvent->subject = $event['subject'];
            $newEvent->template = $event['template'];
            $newEvent->shortcode = $event['shortcode'];
            $newEvent->school_id = 1;
            $newEvent->save();
        }
    }

    public function down()
    {
        Schema::dropIfExists('sm_notification_settings');
    }
}

