<?php

use App\SmPage;
use App\SmsTemplate;
use App\InfixModuleManager;
use App\SmHeaderMenuManager;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Modules\RolePermission\Entities\Permission;
use Modules\RolePermission\Entities\AssignPermission;

return new class extends Migration
{
    public function up()
    {

        Permission::whereNotIn('module',['Saas','Zoom','Gmeet','RazorPay','ParentRegistration','SaasSubscription','OnlineExam','BBB','Jitsi','XenditPayment','Lms','Lead','MercadoPago','CcAveune'])->orWhereNull('module')->delete();

        \Modules\MenuManage\Entities\Sidebar::truncate();
        Schema::table('sm_fees_carry_forwards', function (Blueprint $table) {
            if(!Schema::hasColumn('sm_fees_carry_forwards', 'balance_type')){
                $table->string('balance_type')->nullable();
            }
            if(!Schema::hasColumn('sm_fees_carry_forwards', 'due_date')){
                $table->timestamp('due_date')->nullable();
            }
        });

        Schema::table('sm_exam_types', function (Blueprint $table) {
            if(!Schema::hasColumn('sm_exam_types', 'is_average')){
                $table->tinyInteger('is_average')->default(0)->nullable();
            }
            if(!Schema::hasColumn('sm_exam_types', 'average_mark')){
                $table->float('average_mark')->default(0)->nullable();
            }
            if(!Schema::hasColumn('sm_exam_types', 'percantage')){
                $table->float('percantage')->default(100)->nullable();
            }
        });

        Schema::table('sm_general_settings', function (Blueprint $table) {
            if(!Schema::hasColumn('sm_general_settings', 'InAppLiveClass')){
                $table->tinyInteger('InAppLiveClass')->default(0);
            }

            if(!Schema::hasColumn('sm_general_settings', 'BehaviourRecords')){
                $table->tinyInteger('BehaviourRecords')->default(1);
            }

        });

        Schema::table('sm_events', function (Blueprint $table) {
            if(!Schema::hasColumn('sm_events', 'role_ids')){
                $table->text('role_ids')->nullable();
            }
            if (!Schema::hasColumn('sm_events', 'url')) {
                $table->text('url')->nullable();
            }
        });

        Schema::table('sm_general_settings', function (Blueprint $table) {
            if(!Schema::hasColumn('sm_general_settings', 'BehaviourRecords')){
                $table->integer('BehaviourRecords')->default(0)->nullable();
            }
        });


        $s2 = InfixModuleManager::where('name', 'BehaviourRecords')->first();
        if(!$s2){
            $s2 = new InfixModuleManager();
        }
        $s2->name = "BehaviourRecords";
        $s2->email = 'support@spondonit.com';
        $s2->notes = "This is Behaviour Records Module for manage student behaviour records & Activity. Thanks for using .";
        $s2->version = "1.0";
        $s2->update_url = "https://spondonit.com/contact";
        $s2->is_default = 0;
        $s2->purchase_code = time();
        $s2->addon_url = "https://codecanyon.net/item/google-meet-module-for-infixedu-gmeet-live-class/42463761";
        $s2->installed_domain = url('/');
        $s2->activated_date = date('Y-m-d');
        $s2->save();
        $controller = new \App\Http\Controllers\Admin\SystemSettings\SmAddOnsController();
        $controller->FreemoduleAddOnsEnable("BehaviourRecords");


        $name = 'InAppLiveClass';
        $s = InfixModuleManager::where('name', $name)->first();
        if(!$s){
            $s = new InfixModuleManager();
        }
        $s->name = $name;
        $s->email = 'support@spondonit.com';
        $s->notes = 'This InAppLiveClass Module For InfixEdu. Manage Online Class and Meeting Reports.';
        $s->version = "1.0";
        $s->update_url = "https://spondonit.com/contact";
        $s->is_default = 0;
        $s->installed_domain = url('/');
        $s->save();


        $routine_page = SmPage::where('slug','/class-exam-routine')->first();
        if(!$routine_page){
            $routine_page = new SmPage();
            $routine_page->title = 'Routine';
            $routine_page->slug = '/class-exam-routine';
            $routine_page->active_status = 1;
            $routine_page->is_dynamic = 0;
            $routine_page->save();

            $routine_menu = new SmHeaderMenuManager();
            $routine_menu->type = 'sPages';
            $routine_menu->element_id =  $routine_page->id;
            $routine_menu->title = 'Routine';
            $routine_menu->link = '/class-exam-routine';
            $routine_menu->save();
        }

        $result_page = SmPage::where('slug','/exam-result')->first();
        if(!$result_page){
            $result_page = new SmPage();
            $result_page->title = 'Result';
            $result_page->slug = '/exam-result';
            $result_page->active_status = 1;
            $result_page->is_dynamic = 0;
            $result_page->save();

            $result_menu = new SmHeaderMenuManager();
            $result_menu->type = 'sPages';
            $result_menu->element_id =  $result_page->id;
            $result_menu->title = 'Result';
            $result_menu->link = '/exam-result';
            $result_menu->save();
        }

        $calendarMenus = array(
            'academic-calendar' => array(
                'module' => null,
                'sidebar_menu' => 'communicate',
                'name' => 'Calendar',
                'lang_name' => 'communicate.calendar',
                'icon' => null,
                'svg' => null,
                'route' => 'academic-calendar',
                'parent_route' => 'communicate',
                'is_admin' => 1,
                'is_teacher' => 0,
                'is_student' => 0,
                'is_parent' => 0,
                'position' => 0,
                'is_saas' => 0,
                'is_menu' => 1,
                'status' => 1,
                'menu_status' => 1,
                'relate_to_child' => 0,
                'alternate_module' => null,
                'permission_section' => 0,
                'user_id' => null,
                'type' => 2,
                'old_id' => 291,
                'child' => array(
                    'academic-calendar-settings-view' => array(
                        'module' => null,
                        'sidebar_menu' => null,
                        'name' => 'Calendar Settings View',
                        'lang_name' => 'calendar_settings_view',
                        'icon' => null,
                        'svg' => null,
                        'route' => 'academic-calendar-settings-view',
                        'parent_route' => 'academic-calendar',
                        'is_admin' => 1,
                        'is_teacher' => 0,
                        'is_student' => 0,
                        'is_parent' => 0,
                        'position' => 1,
                        'is_saas' => 0,
                        'is_menu' => NULL,
                        'status' => 1,
                        'menu_status' => 1,
                        'relate_to_child' => 0,
                        'alternate_module' => null,
                        'permission_section' => 0,
                        'user_id' => null,
                        'type' => 3,
                        'old_id' => 88,
                    ),
                    'store-academic-calendar-settings' => array(
                        'module' => null,
                        'sidebar_menu' => null,
                        'name' => 'Calendar Settings',
                        'lang_name' => 'calendar_settings',
                        'icon' => null,
                        'svg' => null,
                        'route' => 'store-academic-calendar-settings',
                        'parent_route' => 'academic-calendar',
                        'is_admin' => 1,
                        'is_teacher' => 0,
                        'is_student' => 0,
                        'is_parent' => 0,
                        'position' => 1,
                        'is_saas' => 0,
                        'is_menu' => NULL,
                        'status' => 1,
                        'menu_status' => 1,
                        'relate_to_child' => 0,
                        'alternate_module' => null,
                        'permission_section' => 0,
                        'user_id' => null,
                        'type' => 3,
                        'old_id' => 88,
                    ),
                ),
            )
        );

        $studentParentMenus = array(
            'academic-calendar' => array(
                'module' => null,
                'sidebar_menu' => null,
                'name' => 'Calendar',
                'lang_name' => 'communicate.calendar',
                'icon' => 'flaticon-poster',
                'svg' => null,
                'route' => 'academic-calendar',
                'parent_route' => null,
                'is_admin' => 0,
                'is_teacher' => 0,
                'is_student' => 1,
                'is_parent' => 1,
                'position' => 16,
                'is_saas' => 0,
                'is_menu' => 1,
                'status' => 1,
                'menu_status' => 1,
                'relate_to_child' => 0,
                'alternate_module' => null,
                'permission_section' => 0,
                'user_id' => null,
                'type' => 1,
                'old_id' => 48,
            ),
        );

        $homeworkReportMenus = array(
            'homework-report' => array(
                'module' => null,
                'sidebar_menu' => 'homework',
                'name' => 'Homework Report',
                'lang_name' => 'homework.homework_report',
                'icon' => null,
                'svg' => null,
                'route' => 'homework-report',
                'parent_route' => 'homework',
                'is_admin' => 1,
                'is_teacher' => 0,
                'is_student' => 0,
                'is_parent' => 0,
                'position' => 0,
                'is_saas' => 0,
                'is_menu' => 1,
                'status' => 1,
                'menu_status' => 1,
                'relate_to_child' => 0,
                'alternate_module' => null,
                'permission_section' => 0,
                'user_id' => null,
                'type' => 2,
                'old_id' => 284,
                'child' => array(
                    'view-homework-report' => array(
                        'module' => null,
                        'sidebar_menu' => null,
                        'name' => 'View',
                        'lang_name' => null,
                        'icon' => null,
                        'svg' => null,
                        'route' => 'view-homework-report',
                        'parent_route' => 'homework-report',
                        'is_admin' => 1,
                        'is_teacher' => 0,
                        'is_student' => 0,
                        'is_parent' => 0,
                        'position' => 0,
                        'is_saas' => 0,
                        'is_menu' => 0,
                        'status' => 1,
                        'menu_status' => 1,
                        'relate_to_child' => 0,
                        'alternate_module' => null,
                        'permission_section' => 0,
                        'user_id' => null,
                        'type' => 3,
                        'old_id' => 285,
                    ),
                ),
            ),
        );

        $notificationSettingMenus =  array('notification_setting' 
            => array(
                'module' => null,
                'sidebar_menu' => 'system_settings',
                'name' => 'Notification Setting',
                'lang_name' => 'system_settings.notification_setting',
                'icon' => null,
                'svg' => null,
                'route' => 'notification_settings',
                'parent_route' => 'general_settings',
                'is_admin' => 1,
                'is_teacher' => 0,
                'is_student' => 0,
                'is_parent' => 0,
                'position' => 13,
                'is_saas' => 0,
                'is_menu' => 1,
                'status' => 1,
                'menu_status' => 1,
                'relate_to_child' => 0,
                'alternate_module' => null,
                'permission_section' => 0,
                'user_id' => null,
                'type' => 2,
                'old_id' => null,
            )
        );

        $feesCarryForwardMenus = array(
            'fees-carry-forward-view' => array(
                'module' => "Fees",
                'sidebar_menu' => 'fees',
                'name' => 'Fees Carry Forward',
                'lang_name' => 'fees.fees_carry_forward',
                'icon' => null,
                'svg' => null,
                'route' => 'fees-carry-forward-view',
                'parent_route' => 'fees',
                'is_admin' => 1,
                'is_teacher' => 0,
                'is_student' => 0,
                'is_parent' => 0,
                'position' => 3,
                'is_saas' => 0,
                'is_menu' => 1,
                'status' => 1,
                'menu_status' => 1,
                'relate_to_child' => 0,
                'alternate_module' => null,
                'permission_section' => 0,
                'user_id' => null,
                'type' => 2,
                'old_id' => 432,
                'child' => array(
                    'fees-carry-forward-search' => array(
                        'module' => null,
                        'sidebar_menu' => null,
                        'name' => 'Search',
                        'lang_name' => null,
                        'icon' => null,
                        'svg' => null,
                        'route' => 'fees-carry-forward-search',
                        'parent_route' => 'fees-carry-forward-view',
                        'is_admin' => 1,
                        'is_teacher' => 0,
                        'is_student' => 0,
                        'is_parent' => 0,
                        'position' => 434,
                        'is_saas' => 0,
                        'is_menu' => 0,
                        'status' => 1,
                        'menu_status' => 1,
                        'relate_to_child' => 0,
                        'alternate_module' => null,
                        'permission_section' => 0,
                        'user_id' => null,
                        'type' => 3,
                        'old_id' => 433,
                    ),
                    'fees-carry-forward-store' => array(
                        'module' => null,
                        'sidebar_menu' => null,
                        'name' => 'Store',
                        'lang_name' => null,
                        'icon' => null,
                        'svg' => null,
                        'route' => 'fees-carry-forward-store',
                        'parent_route' => 'fees-carry-forward-view',
                        'is_admin' => 1,
                        'is_teacher' => 0,
                        'is_student' => 0,
                        'is_parent' => 0,
                        'position' => 434,
                        'is_saas' => 0,
                        'is_menu' => 0,
                        'status' => 1,
                        'menu_status' => 1,
                        'relate_to_child' => 0,
                        'alternate_module' => null,
                        'permission_section' => 0,
                        'user_id' => null,
                        'type' => 3,
                        'old_id' => 433,
                    ),
                ),
            ),
            'fees-carry-forward-settings-view' => array(
                'module' => "Fees",
                'sidebar_menu' => 'fees',
                'name' => 'Fees Carry Forward Settings',
                'lang_name' => 'fees.fees_carry_forward_settings',
                'icon' => null,
                'svg' => null,
                'route' => 'fees-carry-forward-settings-view',
                'parent_route' => 'fees',
                'is_admin' => 1,
                'is_teacher' => 0,
                'is_student' => 0,
                'is_parent' => 0,
                'position' => 3,
                'is_saas' => 0,
                'is_menu' => 1,
                'status' => 1,
                'menu_status' => 1,
                'relate_to_child' => 0,
                'alternate_module' => null,
                'permission_section' => 0,
                'user_id' => null,
                'type' => 2,
                'old_id' => 432,
                'child' => array(
                    'fees-carry-forward-settings-store' => array(
                        'module' => null,
                        'sidebar_menu' => null,
                        'name' => 'Store',
                        'lang_name' => null,
                        'icon' => null,
                        'svg' => null,
                        'route' => 'fees-carry-forward-settings-store',
                        'parent_route' => 'fees-carry-forward-settings-view',
                        'is_admin' => 1,
                        'is_teacher' => 0,
                        'is_student' => 0,
                        'is_parent' => 0,
                        'position' => 434,
                        'is_saas' => 0,
                        'is_menu' => 0,
                        'status' => 1,
                        'menu_status' => 1,
                        'relate_to_child' => 0,
                        'alternate_module' => null,
                        'permission_section' => 0,
                        'user_id' => null,
                        'type' => 3,
                        'old_id' => 433,
                    ),
                ),
            ),
            'fees-carry-forward-log-view' => array(
                'module' => "Fees",
                'sidebar_menu' => 'fees',
                'name' => 'Fees Carry Forward Log',
                'lang_name' => 'fees.fees_carry_forward_log',
                'icon' => null,
                'svg' => null,
                'route' => 'fees-carry-forward-log-view',
                'parent_route' => 'fees',
                'is_admin' => 1,
                'is_teacher' => 0,
                'is_student' => 0,
                'is_parent' => 0,
                'position' => 3,
                'is_saas' => 0,
                'is_menu' => 1,
                'status' => 1,
                'menu_status' => 1,
                'relate_to_child' => 0,
                'alternate_module' => null,
                'permission_section' => 0,
                'user_id' => null,
                'type' => 2,
                'old_id' => 432,
                'child' => array(
                    'fees-carry-forward-log-search' => array(
                        'module' => null,
                        'sidebar_menu' => null,
                        'name' => 'Search',
                        'lang_name' => null,
                        'icon' => null,
                        'svg' => null,
                        'route' => 'fees-carry-forward-log-search',
                        'parent_route' => 'fees-carry-forward-log-view',
                        'is_admin' => 1,
                        'is_teacher' => 0,
                        'is_student' => 0,
                        'is_parent' => 0,
                        'position' => 434,
                        'is_saas' => 0,
                        'is_menu' => 0,
                        'status' => 1,
                        'menu_status' => 1,
                        'relate_to_child' => 0,
                        'alternate_module' => null,
                        'permission_section' => 0,
                        'user_id' => null,
                        'type' => 3,
                        'old_id' => 433,
                    ),
                ),
            ),
            'fees-carry-forward-view-fees-collection' => array(
                'module' => "fees_collection",
                'sidebar_menu' => 'fees',
                'name' => 'Fees Carry Forward',
                'lang_name' => 'fees.fees_carry_forward',
                'icon' => null,
                'svg' => null,
                'route' => 'fees-carry-forward-view-fees-collection',
                'parent_route' => 'fees_collection',
                'is_admin' => 1,
                'is_teacher' => 0,
                'is_student' => 0,
                'is_parent' => 0,
                'position' => 3,
                'is_saas' => 0,
                'is_menu' => 1,
                'status' => 1,
                'menu_status' => 1,
                'relate_to_child' => 0,
                'alternate_module' => null,
                'permission_section' => 0,
                'user_id' => null,
                'type' => 2,
                'old_id' => 432,
                'child' => array(
                    'fees-carry-forward-search' => array(
                        'module' => null,
                        'sidebar_menu' => null,
                        'name' => 'Search',
                        'lang_name' => null,
                        'icon' => null,
                        'svg' => null,
                        'route' => 'fees-carry-forward-search',
                        'parent_route' => 'fees-carry-forward-view-fees-collection',
                        'is_admin' => 1,
                        'is_teacher' => 0,
                        'is_student' => 0,
                        'is_parent' => 0,
                        'position' => 434,
                        'is_saas' => 0,
                        'is_menu' => 0,
                        'status' => 1,
                        'menu_status' => 1,
                        'relate_to_child' => 0,
                        'alternate_module' => null,
                        'permission_section' => 0,
                        'user_id' => null,
                        'type' => 3,
                        'old_id' => 433,
                    ),
                    'fees-carry-forward-store' => array(
                        'module' => null,
                        'sidebar_menu' => null,
                        'name' => 'Store',
                        'lang_name' => null,
                        'icon' => null,
                        'svg' => null,
                        'route' => 'fees-carry-forward-store',
                        'parent_route' => 'fees-carry-forward-view-fees-collection',
                        'is_admin' => 1,
                        'is_teacher' => 0,
                        'is_student' => 0,
                        'is_parent' => 0,
                        'position' => 434,
                        'is_saas' => 0,
                        'is_menu' => 0,
                        'status' => 1,
                        'menu_status' => 1,
                        'relate_to_child' => 0,
                        'alternate_module' => null,
                        'permission_section' => 0,
                        'user_id' => null,
                        'type' => 3,
                        'old_id' => 433,
                    ),
                ),
            ),
            'fees-carry-forward-settings-view-fees-collection' => array(
                'module' => "fees_collection",
                'sidebar_menu' => 'fees',
                'name' => 'Fees Carry Forward Settings',
                'lang_name' => 'fees.fees_carry_forward_settings',
                'icon' => null,
                'svg' => null,
                'route' => 'fees-carry-forward-settings-view-fees-collection',
                'parent_route' => 'fees_collection',
                'is_admin' => 1,
                'is_teacher' => 0,
                'is_student' => 0,
                'is_parent' => 0,
                'position' => 3,
                'is_saas' => 0,
                'is_menu' => 1,
                'status' => 1,
                'menu_status' => 1,
                'relate_to_child' => 0,
                'alternate_module' => null,
                'permission_section' => 0,
                'user_id' => null,
                'type' => 2,
                'old_id' => 432,
                'child' => array(
                    'fees-carry-forward-settings-store' => array(
                        'module' => null,
                        'sidebar_menu' => null,
                        'name' => 'Store',
                        'lang_name' => null,
                        'icon' => null,
                        'svg' => null,
                        'route' => 'fees-carry-forward-settings-store',
                        'parent_route' => 'fees-carry-forward-settings-view-fees-collection',
                        'is_admin' => 1,
                        'is_teacher' => 0,
                        'is_student' => 0,
                        'is_parent' => 0,
                        'position' => 434,
                        'is_saas' => 0,
                        'is_menu' => 0,
                        'status' => 1,
                        'menu_status' => 1,
                        'relate_to_child' => 0,
                        'alternate_module' => null,
                        'permission_section' => 0,
                        'user_id' => null,
                        'type' => 3,
                        'old_id' => 433,
                    ),
                ),
            ),
            'fees-carry-forward-log-view-fees-collection' => array(
                'module' => "fees_collection",
                'sidebar_menu' => 'fees',
                'name' => 'Fees Carry Forward Log',
                'lang_name' => 'fees.fees_carry_forward_log',
                'icon' => null,
                'svg' => null,
                'route' => 'fees-carry-forward-log-view-fees-collection',
                'parent_route' => 'fees_collection',
                'is_admin' => 1,
                'is_teacher' => 0,
                'is_student' => 0,
                'is_parent' => 0,
                'position' => 3,
                'is_saas' => 0,
                'is_menu' => 1,
                'status' => 1,
                'menu_status' => 1,
                'relate_to_child' => 0,
                'alternate_module' => null,
                'permission_section' => 0,
                'user_id' => null,
                'type' => 2,
                'old_id' => 432,
                'child' => array(
                    'fees-carry-forward-log-search' => array(
                        'module' => null,
                        'sidebar_menu' => null,
                        'name' => 'Search',
                        'lang_name' => null,
                        'icon' => null,
                        'svg' => null,
                        'route' => 'fees-carry-forward-log-search',
                        'parent_route' => 'fees-carry-forward-log-view-fees-collection',
                        'is_admin' => 1,
                        'is_teacher' => 0,
                        'is_student' => 0,
                        'is_parent' => 0,
                        'position' => 434,
                        'is_saas' => 0,
                        'is_menu' => 0,
                        'status' => 1,
                        'menu_status' => 1,
                        'relate_to_child' => 0,
                        'alternate_module' => null,
                        'permission_section' => 0,
                        'user_id' => null,
                        'type' => 3,
                        'old_id' => 433,
                    ),
                ),
            ),

            'two_factor_auth_setting' => array(
                'module' => 'TwoFactorAuth',
                'sidebar_menu' => 'system_settings',
                'name' => 'Two Factor Setting',
                'lang_name' => 'auth.two_factor_setting',
                'icon' => null,
                'svg' => null,
                'route' => 'two_factor_auth_setting',
                'parent_route' => 'general_settings',
                'is_admin' => 1,
                'is_teacher' => 0,
                'is_student' => 0,
                'is_parent' => 0,
                'position' => 16,
                'is_saas' => 0,
                'is_menu' => 1,
                'status' => 1,
                'menu_status' => 1,
                'relate_to_child' => 0,
                'alternate_module' => null,
                'permission_section' => 0,
                'user_id' => null,
                'type' => 2,
                'old_id' => null,
            ),
        );
        $teacher_evaluationMenus = array(
            'teacher-evaluation' => array(
                'module' => null,
                'sidebar_menu' => 'teacher-evaluation',
                'name' => 'Teacher Evaluation',
                'lang_name' => 'teacherEvaluation.teacher_evaluation',
                'icon' => 'fas fa-star',
                'svg' => null,
                'route' => 'teacher-evaluation',
                'parent_route' => null,
                'is_admin' => 1,
                'is_teacher' => 1,
                'is_student' => 0,
                'is_parent' => 0,
                'position' => 13,
                'is_saas' => 0,
                'is_menu' => 1,
                'status' => 1,
                'menu_status' => 1,
                'relate_to_child' => 0,
                'alternate_module' => null,
                'permission_section' => 0,
                'user_id' => null,
                'type' => 1,
                'old_id' => null,
                'child' => array(
                    'teacher-approved-evaluation-report' => array(
                        'module' => null,
                        'sidebar_menu' => null,
                        'name' => 'Approved Evaluation Report',
                        'lang_name' => 'teacherEvaluation.approved_evaluation_report',
                        'icon' => null,
                        'svg' => null,
                        'route' => 'teacher-approved-evaluation-report',
                        'parent_route' => 'teacher-evaluation',
                        'is_admin' => 1,
                        'is_teacher' => 0,
                        'is_student' => 0,
                        'is_parent' => 0,
                        'position' => 1,
                        'is_saas' => 0,
                        'is_menu' => 1,
                        'status' => 1,
                        'menu_status' => 1,
                        'relate_to_child' => 0,
                        'alternate_module' => null,
                        'permission_section' => 0,
                        'user_id' => null,
                        'type' => 2,
                        'old_id' => null,
                        'child' => array(
                            'teacher-evaluation-approve-delete' => array(
                                'module' => null,
                                'sidebar_menu' => null,
                                'name' => 'Delete',
                                'lang_name' => null,
                                'icon' => null,
                                'svg' => null,
                                'route' => 'teacher-evaluation-approve-delete',
                                'parent_route' => 'teacher-approved-evaluation-report',
                                'is_admin' => 1,
                                'is_teacher' => 0,
                                'is_student' => 0,
                                'is_parent' => 0,
                                'position' => 1,
                                'is_saas' => 0,
                                'is_menu' => 0,
                                'status' => 1,
                                'menu_status' => 1,
                                'relate_to_child' => 0,
                                'alternate_module' => null,
                                'permission_section' => 0,
                                'user_id' => null,
                                'type' => 3,
                                'old_id' => null,
                            ),
                        ),
                    ),
                    'teacher-pending-evaluation-report' => array(
                        'module' => null,
                        'sidebar_menu' => null,
                        'name' => 'Pending Evaluation Report',
                        'lang_name' => 'teacherEvaluation.pending_evaluation_report',
                        'icon' => null,
                        'svg' => null,
                        'route' => 'teacher-pending-evaluation-report',
                        'parent_route' => 'teacher-evaluation',
                        'is_admin' => 1,
                        'is_teacher' => 0,
                        'is_student' => 0,
                        'is_parent' => 0,
                        'position' => 2,
                        'is_saas' => 0,
                        'is_menu' => 1,
                        'status' => 1,
                        'menu_status' => 1,
                        'relate_to_child' => 0,
                        'alternate_module' => null,
                        'permission_section' => 0,
                        'user_id' => null,
                        'type' => 2,
                        'old_id' => null,
                        'child' => array(
                            'teacher-evaluation-approve-submit' => array(
                                'module' => null,
                                'sidebar_menu' => null,
                                'name' => 'Add',
                                'lang_name' => null,
                                'icon' => null,
                                'svg' => null,
                                'route' => 'teacher-evaluation-approve-submit',
                                'parent_route' => 'teacher-pending-evaluation-report',
                                'is_admin' => 1,
                                'is_teacher' => 0,
                                'is_student' => 0,
                                'is_parent' => 0,
                                'position' => 1,
                                'is_saas' => 0,
                                'is_menu' => 0,
                                'status' => 1,
                                'menu_status' => 1,
                                'relate_to_child' => 0,
                                'alternate_module' => null,
                                'permission_section' => 0,
                                'user_id' => null,
                                'type' => 3,
                                'old_id' => null,
                            ),
                            'teacher-evaluation-approve-delete' => array(
                                'module' => null,
                                'sidebar_menu' => null,
                                'name' => 'Delete',
                                'lang_name' => null,
                                'icon' => null,
                                'svg' => null,
                                'route' => 'teacher-evaluation-approve-delete',
                                'parent_route' => 'teacher-pending-evaluation-report',
                                'is_admin' => 1,
                                'is_teacher' => 0,
                                'is_student' => 0,
                                'is_parent' => 0,
                                'position' => 2,
                                'is_saas' => 0,
                                'is_menu' => 0,
                                'status' => 1,
                                'menu_status' => 1,
                                'relate_to_child' => 0,
                                'alternate_module' => null,
                                'permission_section' => 0,
                                'user_id' => null,
                                'type' => 3,
                                'old_id' => null,
                            ),
                        ),
                    ),
                    'teacher-wise-evaluation-report' => array(
                        'module' => null,
                        'sidebar_menu' => null,
                        'name' => 'Wise Evaluation Report',
                        'lang_name' => 'teacherEvaluation.teacher_wise_evaluation_report',
                        'icon' => null,
                        'svg' => null,
                        'route' => 'teacher-wise-evaluation-report',
                        'parent_route' => 'teacher-evaluation',
                        'is_admin' => 1,
                        'is_teacher' => 0,
                        'is_student' => 0,
                        'is_parent' => 0,
                        'position' => 3,
                        'is_saas' => 0,
                        'is_menu' => 1,
                        'status' => 1,
                        'menu_status' => 1,
                        'relate_to_child' => 0,
                        'alternate_module' => null,
                        'permission_section' => 0,
                        'user_id' => null,
                        'type' => 2,
                        'old_id' => null,
                        'child' => array(
                            'teacher-evaluation-approve-delete' => array(
                                'module' => null,
                                'sidebar_menu' => null,
                                'name' => 'Delete',
                                'lang_name' => null,
                                'icon' => null,
                                'svg' => null,
                                'route' => 'teacher-evaluation-approve-delete',
                                'parent_route' => 'teacher-wise-evaluation-report',
                                'is_admin' => 1,
                                'is_teacher' => 0,
                                'is_student' => 0,
                                'is_parent' => 0,
                                'position' => 1,
                                'is_saas' => 0,
                                'is_menu' => 0,
                                'status' => 1,
                                'menu_status' => 1,
                                'relate_to_child' => 0,
                                'alternate_module' => null,
                                'permission_section' => 0,
                                'user_id' => null,
                                'type' => 3,
                                'old_id' => null,
                            ),
                        ),
                    ),
                    'teacher-evaluation-setting' => array(
                        'module' => null,
                        'sidebar_menu' => null,
                        'name' => 'Setting',
                        'lang_name' => 'teacherEvaluation.settings',
                        'icon' => null,
                        'svg' => null,
                        'route' => 'teacher-evaluation-setting',
                        'parent_route' => 'teacher-evaluation',
                        'is_admin' => 1,
                        'is_teacher' => 0,
                        'is_student' => 0,
                        'is_parent' => 0,
                        'position' => 4,
                        'is_saas' => 0,
                        'is_menu' => 1,
                        'status' => 1,
                        'menu_status' => 1,
                        'relate_to_child' => 0,
                        'alternate_module' => null,
                        'permission_section' => 0,
                        'user_id' => null,
                        'type' => 2,
                        'old_id' => null,
                        'child' => array(
                            'teacher-evaluation-setting-update' => array(
                                'module' => null,
                                'sidebar_menu' => null,
                                'name' => 'Edit',
                                'lang_name' => null,
                                'icon' => null,
                                'svg' => null,
                                'route' => 'teacher-evaluation-setting-update',
                                'parent_route' => 'teacher-evaluation-setting',
                                'is_admin' => 1,
                                'is_teacher' => 0,
                                'is_student' => 0,
                                'is_parent' => 0,
                                'position' => 1,
                                'is_saas' => 0,
                                'is_menu' => 0,
                                'status' => 1,
                                'menu_status' => 1,
                                'relate_to_child' => 0,
                                'alternate_module' => null,
                                'permission_section' => 0,
                                'user_id' => null,
                                'type' => 3,
                                'old_id' => null,
                            ),
                        ),
                    ),
                    'teacher-panel-evaluation-report' => array(
                        'module' => null,
                        'sidebar_menu' => null,
                        'name' => 'My Report',
                        'lang_name' => 'teacherEvaluation.my_report',
                        'icon' => null,
                        'svg' => null,
                        'route' => 'teacher-panel-evaluation-report',
                        'parent_route' => 'teacher-evaluation',
                        'is_admin' => 0,
                        'is_teacher' => 1,
                        'is_student' => 0,
                        'is_parent' => 0,
                        'position' => 5,
                        'is_saas' => 0,
                        'is_menu' => 1,
                        'status' => 1,
                        'menu_status' => 1,
                        'relate_to_child' => 0,
                        'alternate_module' => null,
                        'permission_section' => 0,
                        'user_id' => null,
                        'type' => 2,
                        'old_id' => null,
                    ),
                ),
            )
        );

        // this file will be first
        $adminPermissionList = include('./resources/var/permission/without_student_parent_positions.php');
        foreach($adminPermissionList as $item){           
            storePermissionData($item);
        }
        // first file end
        $studentPermissionList = include('./resources/var/permission/student_permissions.php');
        foreach($studentPermissionList as $item){           
            storePermissionData($item);
        }
        $parentPermissionList = include('./resources/var/permission/parent_permissions.php');
        foreach($parentPermissionList as $item){           
            storePermissionData($item);
        }
        //this file will be last
        $permissionSections = include('./resources/var/permission/permission_section_sidebar.php');
        foreach($permissionSections as $item){           
            storePermissionData($item , 1, 1, 1);
        } 


        $emailTemplates = [
            [
                'email', 'leave_applied', 'Leave Applied',
                '<table bgcolor="#FFFFFF" cellpadding="0" cellspacing="0" class="nl-container"
            style="table-layout:fixed;vertical-align:top;min-width:320px;border-spacing:0;border-collapse:collapse;background-color:#FFFFFF;width:100%;"
            width="100%">
            <tbody>
                <tr style="vertical-align:top;" valign="top">
                    <td style="vertical-align:top;" valign="top">
                        <div style="background-color:#415094;">
                            <div class="block-grid"
                                style="min-width:320px;max-width:600px;margin:0 auto;background-color:transparent;">
                                <div
                                    style="border-collapse:collapse;width:100%;background-color:transparent;background-position:center top;background-repeat:no-repeat;">
                                    <div class="col num12"
                                        style="min-width:320px;max-width:600px;vertical-align:top;width:600px;">
                                        <div class="col_cont" style="width:100%;">
                                            <div align="center" class="img-container center fixedwidth"
                                                style="padding-right:30px;padding-left:30px;">
                                                <a href="#">
                                                    <img border="0" class="center fixedwidth" src=""
                                                        style="padding-top:30px;padding-bottom:30px;text-decoration:none;height:auto;border:0;max-width:150px;"
                                                        width="150" alt="logo.png">
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div style="background-color:#415094;">
                            <div class="block-grid"
                                style="min-width:320px;max-width:600px;margin:0 auto;background-color:#ffffff;padding-top:25px;border-top-right-radius:30px;border-top-left-radius:30px;">
                                <div style="border-collapse:collapse;width:100%;background-color:transparent;">
                                    <div class="col num12"
                                        style="min-width:320px;max-width:600px;vertical-align:top;width:600px;">
                                        <div class="col_cont" style="width:100%;">
                                            <div align="center" class="img-container center autowidth"
                                                style="padding-right:20px;padding-left:20px;">
                                                <img border="0" class="center autowidth"
                                                    src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRGF00Oi-zJNU_EvYGueBVz_sqXmFjk8pxNtg&amp;usqp=CAU"
                                                    style="text-decoration:none;height:auto;border:0;max-width:541px;"
                                                    width="541"
                                                    alt="images?q=tbn:ANd9GcRGF00Oi-zJNU_EvYGueBVz_sqXmFjk8pxNtg&amp;usqp=CAU">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div style="background-color:#7c32ff;">
                            <div class="block-grid"
                                style="min-width:320px;max-width:600px;margin:0 auto;background-color:#ffffff;border-bottom-right-radius:30px;border-bottom-left-radius:30px;overflow:hidden;">
                                <div style="border-collapse:collapse;width:100%;background-color:#ffffff;">
                                    <div class="col num12"
                                        style="min-width:320px;max-width:600px;vertical-align:top;width:600px;">
                                        <div class="col_cont" style="width:100%;">
                                            <h1 style="line-height:120%;text-align:center;margin-bottom:0px;">
                                                <font color="#555555" face="Arial, Helvetica Neue, Helvetica, sans-serif">
                                                    <span style="font-size:36px;">Leave Applied</span>
                                                </font>
                                            </h1>
                                            <div style="line-height:1.8;padding:20px 15px;">
                                                <div class="txtTinyMce-wrapper" style="line-height:1.8;">
                                                    <h1>Dear Admin,</h1>
                                                    <p
                                                        style="margin:10px 0px 30px;line-height:1.929;font-size:16px;color:rgb(113,128,150);">
                                                        A [role] named [name] applied for a leave on [apply_date] from [leave_from] to [leave_to] for reason [reason].
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div style="background-color:#7c32ff;">
                            <div class="block-grid"
                                style="min-width:320px;max-width:600px;margin:0 auto;background-color:transparent;">
                                <div style="border-collapse:collapse;width:100%;background-color:transparent;">
                                    <div class="col num12"
                                        style="min-width:320px;max-width:600px;vertical-align:top;width:600px;">
                                        <div class="col_cont" style="width:100%;">
                                            <div
                                                style="color:#262b30;font-family:Arial, Helvetica Neue, Helvetica, sans-serif;line-height:1.2;padding-top:30px;padding-right:5px;padding-bottom:5px;padding-left:5px;">
                                                <div class="txtTinyMce-wrapper"
                                                    style="line-height:1.2;font-size:12px;font-family:Arial, Helvetica Neue, Helvetica, sans-serif;color:#262b30;">
                                                    <p
                                                        style="margin:0;font-size:12px;line-height:1.2;text-align:center;margin-top:0;margin-bottom:0;">
                                                        <span style="font-size:14px;color:rgb(255,255,255);font-family:Arial;">
                                                            © 2024 Infix Education software|
                                                        </span>
                                                        <span style="background-color:transparent;text-align:left;">
                                                            <font color="#ffffff">
                                                                Copyright &copy; 2024 All rights reserved | This application is
                                                                made by Codethemes
                                                            </font>
                                                        </span>
                                                        <br>
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </td>
                </tr>
            </tbody>
            </table>', '', '[name], [email], [role], [apply_date], [leave_from], [leave_to], [reason]'
            ],

            [
                'email', 'leave_notification', 'Leave Notification',
                '<table bgcolor="#FFFFFF" cellpadding="0" cellspacing="0" class="nl-container"
                style="table-layout:fixed;vertical-align:top;min-width:320px;border-spacing:0;border-collapse:collapse;background-color:#FFFFFF;width:100%;"
                width="100%">
                <tbody>
                    <tr style="vertical-align:top;" valign="top">
                        <td style="vertical-align:top;" valign="top">
                            <div style="background-color:#415094;">
                                <div class="block-grid"
                                    style="min-width:320px;max-width:600px;margin:0 auto;background-color:transparent;">
                                    <div
                                        style="border-collapse:collapse;width:100%;background-color:transparent;background-position:center top;background-repeat:no-repeat;">
                                        <div class="col num12"
                                            style="min-width:320px;max-width:600px;vertical-align:top;width:600px;">
                                            <div class="col_cont" style="width:100%;">
                                                <div align="center" class="img-container center fixedwidth"
                                                    style="padding-right:30px;padding-left:30px;">
                                                    <a href="#">
                                                        <img border="0" class="center fixedwidth" src=""
                                                            style="padding-top:30px;padding-bottom:30px;text-decoration:none;height:auto;border:0;max-width:150px;"
                                                            width="150" alt="logo.png">
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div style="background-color:#415094;">
                                <div class="block-grid"
                                    style="min-width:320px;max-width:600px;margin:0 auto;background-color:#ffffff;padding-top:25px;border-top-right-radius:30px;border-top-left-radius:30px;">
                                    <div style="border-collapse:collapse;width:100%;background-color:transparent;">
                                        <div class="col num12"
                                            style="min-width:320px;max-width:600px;vertical-align:top;width:600px;">
                                            <div class="col_cont" style="width:100%;">
                                                <div align="center" class="img-container center autowidth"
                                                    style="padding-right:20px;padding-left:20px;">
                                                    <img border="0" class="center autowidth"
                                                        src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRGF00Oi-zJNU_EvYGueBVz_sqXmFjk8pxNtg&amp;usqp=CAU"
                                                        style="text-decoration:none;height:auto;border:0;max-width:541px;"
                                                        width="541"
                                                        alt="images?q=tbn:ANd9GcRGF00Oi-zJNU_EvYGueBVz_sqXmFjk8pxNtg&amp;usqp=CAU">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div style="background-color:#7c32ff;">
                                <div class="block-grid"
                                    style="min-width:320px;max-width:600px;margin:0 auto;background-color:#ffffff;border-bottom-right-radius:30px;border-bottom-left-radius:30px;overflow:hidden;">
                                    <div style="border-collapse:collapse;width:100%;background-color:#ffffff;">
                                        <div class="col num12"
                                            style="min-width:320px;max-width:600px;vertical-align:top;width:600px;">
                                            <div class="col_cont" style="width:100%;">
                                                <h1 style="line-height:120%;text-align:center;margin-bottom:0px;">
                                                    <font color="#555555" face="Arial, Helvetica Neue, Helvetica, sans-serif">
                                                        <span style="font-size:36px;">Leave Notification</span>
                                                    </font>
                                                </h1>
                                                <div style="line-height:1.8;padding:20px 15px;">
                                                    <div class="txtTinyMce-wrapper" style="line-height:1.8;">
                                                        <h1>Dear [name],</h1>
                                                        <p
                                                            style="margin:10px 0px 30px;line-height:1.929;font-size:16px;color:rgb(113,128,150);">
                                                            Your application for leave on [apply_date] from [leave_from] to [leave_to] for reason [reason] is [status].
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div style="background-color:#7c32ff;">
                                <div class="block-grid"
                                    style="min-width:320px;max-width:600px;margin:0 auto;background-color:transparent;">
                                    <div style="border-collapse:collapse;width:100%;background-color:transparent;">
                                        <div class="col num12"
                                            style="min-width:320px;max-width:600px;vertical-align:top;width:600px;">
                                            <div class="col_cont" style="width:100%;">
                                                <div
                                                    style="color:#262b30;font-family:Arial, Helvetica Neue, Helvetica, sans-serif;line-height:1.2;padding-top:30px;padding-right:5px;padding-bottom:5px;padding-left:5px;">
                                                    <div class="txtTinyMce-wrapper"
                                                        style="line-height:1.2;font-size:12px;font-family:Arial, Helvetica Neue, Helvetica, sans-serif;color:#262b30;">
                                                        <p
                                                            style="margin:0;font-size:12px;line-height:1.2;text-align:center;margin-top:0;margin-bottom:0;">
                                                            <span style="font-size:14px;color:rgb(255,255,255);font-family:Arial;">
                                                                © 2024 Infix Education software|
                                                            </span>
                                                            <span style="background-color:transparent;text-align:left;">
                                                                <font color="#ffffff">
                                                                    Copyright &copy; 2024 All rights reserved | This application is
                                                                    made by Codethemes
                                                                </font>
                                                            </span>
                                                            <br>
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>', '', '[name], [email], [role], [apply_date], [leave_from], [leave_to], [reason], [status]'
            ],
        ];

        foreach ($emailTemplates as $allTemplete) {
            $storeTemplete = new SmsTemplate();
            $storeTemplete->type = $allTemplete[0];
            $storeTemplete->purpose = $allTemplete[1];
            $storeTemplete->subject = $allTemplete[2];
            $storeTemplete->body = $allTemplete[3];
            $storeTemplete->module = $allTemplete[4];
            $storeTemplete->variable = $allTemplete[5];
            $storeTemplete->save();
        }
        $admins = 
        [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 24, 25, 26, 27, 28, 29, 30, 31, 32, 33, 34, 35, 36, 37, 38, 39, 40, 41, 42, 43, 44, 45, 46, 47, 48, 49, 50, 51, 52, 53, 54, 55, 56, 57, 58, 59, 60, 61, 62, 63, 64, 65, 66, 67, 68, 69, 70, 71, 72, 73, 74, 75, 76, 77, 79, 80, 81, 82, 83, 84, 85, 86, 533, 534, 535, 536, 87, 88, 89, 90, 91, 92, 93, 94, 95, 100, 101, 102, 103, 104, 105, 106, 107, 108, 109, 110, 111, 112, 113, 114, 115, 116, 117, 118, 119, 120, 121, 122, 123, 124, 125, 126, 127, 128, 129, 130, 131, 132, 133, 134, 135, 160, 161, 162, 163, 164, 165, 166, 167, 168, 169, 170, 171, 172, 173, 174, 175, 176, 177, 178, 179, 180, 181, 182, 183, 184, 185, 186, 187, 188, 189, 190, 191, 192, 193, 194, 195, 196, 197, 198, 199, 200, 201, 202, 203, 204, 205, 206, 207, 208, 209, 210, 211, 214, 215, 216, 217, 218, 219, 225, 226, 227, 228, 229, 230, 231, 232, 233, 234, 235, 236, 237, 238, 239, 240, 241, 242, 243, 244, 245, 246, 247, 248, 249, 250, 251, 252, 253, 254, 255, 256, 257, 258, 259, 260, 261, 262, 263, 264, 265, 266, 267, 268, 269, 270, 271, 272, 273, 274, 275, 276, 537, 286, 287, 288, 289, 290, 291, 292, 293, 294, 295, 296, 297, 298, 299, 300, 301, 302, 303, 304, 305, 306, 307, 308, 309, 310, 311, 312, 313, 314, 315, 316, 317, 318, 319, 320, 321, 322, 323, 324, 325, 326, 327, 328, 329, 330, 331, 332, 333, 334, 335, 336, 337, 338, 339, 340, 341, 342, 343, 344, 345, 346, 347, 348, 349, 350, 351, 352, 353, 354, 355, 356, 357, 358, 359, 360, 361, 362, 363, 364, 365, 366, 367, 368, 369, 370, 371, 372, 373, 374, 375, 376, 377, 378, 379, 380, 381, 382, 383, 384, 385, 386, 387, 388, 389, 390, 391, 392, 394, 395, 396, 397, 538, 539, 540, 485, 486, 487, 488, 489, 490, 491,553,577,800,801,802,803,804,805,806,807,808,809,810,811,812,813,814,815,900,901,902,903,904];

        $adminPermissionInfos = Permission::whereIn('old_id', $admins)->where('is_admin', 1)->get(['id', 'name']);
        foreach ($adminPermissionInfos as $key => $permission) 
        {
            $assignPermission = new AssignPermission();
            $assignPermission->permission_id = $permission->id;           
            $assignPermission->role_id = 5;
            $assignPermission->save();
        }

        // for teacher
        $teachers = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 61, 62, 63, 64, 65, 66, 67, 68, 69, 70, 71, 72, 73, 74, 75, 76, 77, 79, 80, 81, 82, 83, 84, 85, 86, 533, 534, 535, 536, 87, 88, 89, 90, 91, 92, 93, 94, 95, 100, 101, 102, 103, 104, 105, 106, 107, 160, 161, 162, 163, 164, 165, 166, 167, 168, 169, 170, 171, 172, 173, 174, 175, 176, 177, 178, 179, 180, 181, 182, 183, 184, 185, 186, 187, 188, 189, 190, 191, 192, 193, 194, 195, 196, 197, 198, 199, 200, 201, 202, 203, 204, 205, 206, 207, 208, 209, 210, 211, 214, 215, 216, 217, 218, 219, 225, 226, 227, 228, 229, 230, 231, 232, 233, 234, 235, 236, 237, 238, 239, 240, 241, 242, 243, 244, 245, 246, 247, 248, 249, 250, 251, 252, 253, 254, 255, 256, 257, 258, 259, 260, 261, 262, 263, 264, 265, 266, 267, 268, 269, 270, 271, 272, 273, 274, 275, 276, 537, 286, 287, 288, 289, 290, 291, 292, 293, 294, 295, 296, 297, 298, 299, 300, 301, 302, 303, 304, 305, 306, 307, 308, 309, 310, 311, 312, 313, 314, 348, 349, 350, 351, 352, 353, 354, 355, 356, 357, 358, 359, 360, 361, 362, 363, 364, 365, 366, 367, 368, 369, 370, 371, 372, 373, 374, 375, 277, 278, 279, 280, 281, 282, 283, 284, 285,553,800,801,802,803,804,805,806,807,808,809,833,834,900,901,902,903,904];

        $teachersInfos = Permission::whereIn('old_id', $teachers)->where('is_admin', 1)->orWhere('is_teacher', 1)->get(['id', 'name']);
        foreach ($teachersInfos as $key => $permission) {           
            $assignPermission = new AssignPermission();
            $assignPermission->permission_id = $permission->id;           
            $assignPermission->role_id = 4;
            $assignPermission->save();
        }
       
        // for receptionists
        $receptionists = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 24, 25, 26, 27, 28, 29, 30, 31, 32, 33, 34, 35, 36, 37, 38, 39, 40, 41, 42, 43, 44, 45, 46, 47, 48, 49, 50, 51, 52, 53, 54, 55, 56, 57, 58, 59, 60, 61, 64, 65, 66, 67, 83, 84, 85, 86, 160, 161, 162, 163, 164, 188, 193, 194, 195, 376, 377, 378, 379, 380,553, 900,901,902,903,904];

        $receptionistInfo = Permission::whereIn('old_id', $receptionists)->where('is_admin', 1)->get(['id', 'name']);
        foreach ($receptionistInfo as $key => $permission) {
            $assignPermission = new AssignPermission();
            $assignPermission->permission_id = $permission->id;     
            $assignPermission->role_id = 7;
            $assignPermission->save();
        }

        // for librarians
        $librarians = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 61, 64, 65, 66, 67, 83, 84, 85, 86, 160, 161, 162, 163, 164, 188, 193, 194, 195, 298, 299, 300, 301, 302, 303, 304, 305, 306, 307, 308, 309, 310, 311, 312, 313, 314, 376, 377, 378, 379, 380,553,900,901,902,903,904];

        $librariansInfo = Permission::whereIn('old_id', $librarians)->where('is_admin', 1)->get(['id', 'name']);

        foreach ($librariansInfo as $key => $permission) {
            $assignPermission = new AssignPermission();
            $assignPermission->permission_id = $permission->id;
            $assignPermission->role_id = 8;
            $assignPermission->save();
        }

        // for drivers
        $drivers = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 188, 193, 194, 19,553,900,901,902,903,904];
        $driverInfos = Permission::whereIn('old_id', $drivers)->where('is_admin', 1)->get(['id', 'name']);
        foreach ($driverInfos as $key => $permission) {
            $assignPermission = new AssignPermission();
            $assignPermission->permission_id = $permission->id;
            $assignPermission->role_id = 9;
            $assignPermission->save();
        }

        // for accountants
        $accountants = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 24, 25, 26, 27, 28, 29, 30, 31, 32, 33, 34, 35, 36, 37, 38, 39, 40, 41, 42, 43, 44, 45, 46, 47, 48, 49, 50, 51, 52, 53, 54, 55, 56, 57, 58, 59, 60, 61, 64, 65, 66, 67, 68, 69, 70, 83, 84, 85, 86, 108, 109, 110, 111, 112, 113, 114, 115, 116, 117, 118, 119, 120, 121, 122, 123, 124, 125, 126, 127, 128, 129, 130, 131, 132, 133, 134, 135, 160, 161, 162, 163, 164, 165, 166, 167, 168, 169, 170, 171, 172, 173, 174, 175, 176, 177, 178, 179, 188, 193, 194, 195, 376, 377, 378, 379, 380, 381, 382, 383,553,900,901,902,903,904];

        $accountantsInfos = Permission::whereIn('old_id', $accountants)->where('is_admin', 1)->get(['id', 'name']);
        foreach ($accountantsInfos as $key => $permission) {
            $assignPermission = new AssignPermission();
            $assignPermission->permission_id = $permission->id;
            $assignPermission->role_id = 6;
            $assignPermission->save();
        }

        // student
        for ($j = 1; $j <= 55; $j++) {
            $permission = new AssignPermission();
            $permission->permission_id = @Permission::where('old_id', $j)->where('is_student', 1)->value('id');
            $permission->role_id = 2;
            $permission->save();
        }    

        //  Student for Chat Module

        $students = [900,901,902,903,904, 800,810,815,1124,1125,1126,1156];
        $chatPermissionInfoStudents = Permission::whereIn('old_id', $students)->where('is_student', 1)->get(['id', 'name']);
        foreach ($chatPermissionInfoStudents as $key => $permission) {
            $assignPermission = new AssignPermission();
            $assignPermission->permission_id = $permission->id;
            $assignPermission->role_id = 2;
            $assignPermission->save();
        }

      

        // parent
        for ($j = 56; $j <= 99; $j++) {
            $permission = new AssignPermission();
            $permission->permission_id = @Permission::where('old_id', $j)->where('is_parent', 1)->value('id');         
            $permission->role_id = 3;
            $permission->save();
        }

        // Parent for Online Exam &  Chat Module

        $parentInfos = [910,911,912,913,914,2016,2017,2018,1127,1128,1129,1157];
        $parentPermissionInfos = Permission::whereIn('old_id', $parentInfos)->where('is_parent', 1)->get(['id', 'name']);
        foreach ($parentPermissionInfos as $key => $permission) {
            $permission = new AssignPermission();
            $permission->permission_id = $permission->id;           
            $permission->role_id = 3;
            $permission->save();
        }
        
    }

    public function down(): void
    {
        //
    }
};
