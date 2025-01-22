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
       
        foreach($calendarMenus as $calendarMenu){           
            storePermissionData($calendarMenu);
        }

        foreach($studentParentMenus as $studentParentMenu){           
            storePermissionData($studentParentMenu);
        }

        $permission = new Permission();          
        $permission->id = 1;
        $permission->module = null;
        $permission->sidebar_menu = null;
        $permission->old_id = null;
        $permission->section_id = 1;
        $permission->parent_id = 0;
        $permission->name = null;
        $permission->route =null;
        $permission->parent_route =null;
        $permission->type = null;
        $permission->lang_name = null;
        $permission->icon = null;
        $permission->svg = null;
        $permission->status = 1;
        $permission->menu_status = 1;
        $permission->position = 1;
        $permission->is_saas = 0;
        $permission->relate_to_child = 0;
        $permission->is_menu = 1;
        $permission->is_admin = 1;
        $permission->is_teacher = 0;
        $permission->is_student =1;
        $permission->is_parent = 1;
        $permission->created_by = 1;
        $permission->updated_by = 1;
        $permission->permission_section = 1;
        $permission->alternate_module = null;
        $permission->user_id = null;
        $permission->school_id  = 1;
        $permission->save();   
        
        
        $beh = Permission::where('sidebar_menu','behaviour_records')->whereNull('parent_route')->first();
        $beh_com = Permission::where('route','behaviour_records.incident_comment')->first();
        if($beh){
            $beh->is_student = 1; 
            $beh->is_parent = 1; 
            $beh->save(); 
        }
        if($beh_com){
            $beh_com->is_student = 1; 
            $beh_com->is_parent = 1; 
            $beh_com->save(); 
        }

        
    }

    public function down(): void
    {
        //
    }
};
