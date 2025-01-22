<?php

use App\SmGeneralSettings;
use App\InfixModuleManager;
use App\Models\SmNotificationSetting;
use App\SmHeaderMenuManager;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Auth;
use Modules\RolePermission\Entities\Permission;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $generalSettings = SmGeneralSettings::first();
        if ($generalSettings) {
            $generalSettings->software_version = '8.2.3';
            $generalSettings->update();
        }

        $exist = InfixModuleManager::where('name', 'News')->first();
        if (!$exist) {
            $name = 'News';
            $s = new InfixModuleManager();
            $s->name = $name;
            $s->email = 'support@spondonit.com';
            $s->notes = "Welcome to the News Module: Keeping Your Organization Connected. Thank You for Using Our Service";
            $s->version = "1.0";
            $s->update_url = "https://spondonit.com/contact";
            $s->is_default = 0;
            $s->addon_url = "https://codecanyon.net/item/infixedu-zoom-live-class/27623128?s_rank=12";
            $s->installed_domain = url('/');
            $s->activated_date = date('Y-m-d');
            $s->save();
        }

        $exist = InfixModuleManager::where('name', 'PDF')->first();
        if (!$exist) {
            $name = 'PDF';
            $s = new InfixModuleManager();
            $s->name = $name;
            $s->email = 'support@spondonit.com';
            $s->notes = "Welcome to the PDF Module: Thanks for using";
            $s->version = "1.0";
            $s->update_url = "https://spondonit.com/contact";
            $s->is_default = 0;
            $s->addon_url = "https://codecanyon.net/item/infixedu-zoom-live-class/27623128?s_rank=12";
            $s->installed_domain = url('/');
            $s->activated_date = date('Y-m-d');
            $s->save();
        }

        $exist = InfixModuleManager::where('name', 'SaasHr')->first();
        if (!$exist) {
            $name = 'SaasHr';
            $s = new InfixModuleManager();
            $s->name = $name;
            $s->email = 'support@spondonit.com';
            $s->notes = "Welcome to the Saas Human Resource Module: Thanks for using";
            $s->version = "1.0";
            $s->update_url = "https://spondonit.com/contact";
            $s->is_default = 0;
            $s->addon_url = "https://codecanyon.net/item/infixedu-zoom-live-class/27623128?s_rank=12";
            $s->installed_domain = url('/');
            $s->activated_date = date('Y-m-d');
            $s->save();
        }

        $exist = InfixModuleManager::where('name', 'Forum')->first();
        if (!$exist) {
            $name = 'Forum';
            $s = new InfixModuleManager();
            $s->name = $name;
            $s->email = 'support@spondonit.com';
            $s->notes = "Welcome to the Forum, Module: Thanks for using";
            $s->version = "1.0";
            $s->update_url = "https://spondonit.com/contact";
            $s->is_default = 0;
            $s->addon_url = "https://codecanyon.net/item/infixedu-zoom-live-class/27623128?s_rank=12";
            $s->installed_domain = url('/');
            $s->activated_date = date('Y-m-d');
            $s->save();
        }

        $exist = InfixModuleManager::where('name', 'CustomMenu')->first();
        if (!$exist) {
            $name = 'CustomMenu';
            $s = new InfixModuleManager();
            $s->name = $name;
            $s->email = 'support@spondonit.com';
            $s->notes = "Welcome to the CustomMenu, Module: Thanks for using";
            $s->version = "1.0";
            $s->update_url = "https://spondonit.com/contact";
            $s->is_default = 0;
            $s->addon_url = "https://codecanyon.net/item/infixedu-zoom-live-class/27623128?s_rank=12";
            $s->installed_domain = url('/');
            $s->activated_date = date('Y-m-d');
            $s->save();
        }

        if (!Schema::hasColumn('sm_general_settings', 'is_custom_saas')) {
            Schema::table('sm_general_settings', function (Blueprint $table) {
                $table->integer('is_custom_saas')->default(0);
            });
        }
        
        $permissions = Permission::whereIn('route', ['pagebuilder.footer', 'pagebuilder.header'])->get();

        foreach ($permissions as $permission) {
            $needsUpdate = false;
        
            if ($permission->status == 0) {
                $permission->status = 1;
                $needsUpdate = true;
            }
        
            if ($permission->menu_status == 0) {
                $permission->menu_status = 1;
                $needsUpdate = true;
            }
        
            if ($needsUpdate) {
                $permission->save();
            }
        }

        Schema::table('sm_notification_settings', function (Blueprint $table) {
            if(Schema::hasColumn('sm_notification_settings', 'shortcode')){
                $table->text('shortcode')->change();
            }
        });

        $exist = SmNotificationSetting::whereIn('event', ['Leave_Apply', 'Leave_Approved', 'Leave_Declined'])->delete();

        $all_events = [
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

        # delete existing lang notification file on the location of  resources/lang/en/notification.php
        $path = base_path('resources/lang/en/notification.php');

        if (file_exists($path)) {
            try {
            unlink($path);
           } catch (\Throwable $th) {
           
           }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
