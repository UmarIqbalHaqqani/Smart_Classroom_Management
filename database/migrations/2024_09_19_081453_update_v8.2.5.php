<?php

use App\SmGeneralSettings;
use App\InfixModuleManager;
use App\SmHeaderMenuManager;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
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
            $generalSettings->software_version = '8.2.5';
            $generalSettings->update();
        }

        Permission::where('route','student_transport_report')->where('parent_route','students_report')->where('type',2)->where('school_id',1)->delete();
        Permission::where('route','student_dormitory_report')->where('parent_route','students_report')->where('type',2)->where('school_id',1)->delete();
        
        $permissions =  array(
            'student_transport_report' => array(
                'module' => null,
                'sidebar_menu' => 'students_report',
                'name' => 'Student Transport Report',
                'lang_name' => 'transport.student_transport_report',
                'icon' => null,
                'svg' => null,
                'route' => 'student_transport_report_index',
                'parent_route' => 'students_report',
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
                'old_id' => 361,
            ),
            'student_dormitory_report' => array(
                'module' => null,
                'sidebar_menu' => 'students_report',
                'name' => 'Student Dormitory Report',
                'lang_name' => 'dormitory.student_dormitory_report',
                'icon' => null,
                'svg' => null,
                'route' => 'student_dormitory_report_index',
                'parent_route' => 'students_report',
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
                'old_id' => 375,
            ),
            'update-my-profile' => array(
                'module' => null,
                'sidebar_menu' => null,
                'name' => 'Update',
                'lang_name' => null,
                'icon' => null,
                'svg' => null,
                'route' => 'update-my-profile',
                'parent_route' => 'student-profile.profile',
                'is_admin' => 0,
                'is_teacher' => 0,
                'is_student' => 1,
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
                'old_id' => 12,
            ),
            'my-children-update' => array(
                'module' => null,
                'sidebar_menu' => null,
                'name' => 'Update',
                'lang_name' => null,
                'icon' => null,
                'svg' => null,
                'route' => 'my-children-update',
                'parent_route' => 'my_children',
                'is_admin' => 0,
                'is_teacher' => 0,
                'is_student' => 0,
                'is_parent' => 1,
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
                'old_id' => 12,
            ),
            'viewStaff' => array(
                'module' => null,
                'sidebar_menu' => null,
                'name' => 'View Staff',
                'lang_name' => null,
                'icon' => null,
                'svg' => null,
                'route' => 'viewStaff',
                'parent_route' => 'staff_directory',
                'is_admin' => 1,
                'is_teacher' => 0,
                'is_student' => 0,
                'is_parent' => 1,
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
                'old_id' => 13,
            ),
        );
        foreach ($permissions as $newsPermission) {
            storePermissionData($newsPermission);
        }

        # Delete exra route menu from permission table
        $fees_setting    = Permission::where('route','fees_settings')->where('parent_route','settings_section')->where('type',null)->whereNull('user_id')->where('role_id',1)->where('school_id',1)->delete();
        $exam_settings   = Permission::where('route','exam_settings')->where('parent_route','settings_section')->where('type',null)->whereNull('user_id')->where('role_id',1)->where('school_id',1)->delete();
        $student_report  = Permission::where('route','students_report')->where('parent_route','report_section')->where('type',null)->whereNull('user_id')->where('role_id',1)->where('school_id',1)->delete();
        $exam_report     = Permission::where('route','exam_report')->where('parent_route','report_section')->where('type',null)->whereNull('user_id')->where('role_id',1)->where('school_id',1)->delete();
        $staff_report    = Permission::where('route','staff_report')->where('parent_route','report_section')->where('type',null)->whereNull('user_id')->where('role_id',1)->where('school_id',1)->delete();
        $fees_report     = Permission::where('route','fees_report')->where('parent_route','report_section')->where('type',null)->whereNull('user_id')->where('role_id',1)->where('school_id',1)->delete();
        $accounts_report = Permission::where('route','accounts_report')->where('parent_route','report_section')->where('type',null)->whereNull('user_id')->where('role_id',1)->where('school_id',1)->delete();
    
        #update event_des column string to longtext
        Schema::table('sm_events', function (Blueprint $table) {
            $table->longText('event_des')->change();
        });

        $jitsi = Permission::where('module','Jitsi')->where('route','jitsi')->where('school_id',1)->first();
        if($jitsi){
            $jitsi->menu_status = 1;
            $jitsi->update();
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
