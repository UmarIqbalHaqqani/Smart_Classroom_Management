<?php

use App\SmGeneralSettings;
use App\InfixModuleManager;
use App\SmHeaderMenuManager;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Modules\RolePermission\Entities\Permission;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $permissions =  array(
            'import-staff' => array(
                'module' => null,
                'sidebar_menu' => 'human_resource',
                'name' => 'Import Staff',
                'lang_name' => 'fees::en.feesModule.import-staff',
                'icon' => null,
                'svg' => null,
                'route' => 'import-staff',
                'parent_route' => 'human_resource',
                'is_admin' => 1,
                'is_teacher' => 0,
                'is_student' => 0,
                'is_parent' => 0,
                'position' => 3,
                'is_saas' => 0,
                'is_menu' => 0,
                'status' => 1,
                'menu_status' => 0,
                'relate_to_child' => 0,
                'alternate_module' => null,
                'permission_section'    => 0,
                'section_id' => 1,
                'user_id' => null,
                'type' => 3,
                'old_id' => 712,
                'child' => array(),
            ),
            'weekend.store' => array(
                'module' => null,
                'sidebar_menu' => 'system_settings',
                'name' => 'Weekend Store',
                'lang_name' => 'common.weekend-store',
                'icon' => null,
                'svg' => null,
                'route' => 'weekend.store',
                'parent_route' => 'general_settings',
                'is_admin' => 1,
                'is_teacher' => 0,
                'is_student' => 0,
                'is_parent' => 0,
                'position' => 3,
                'is_saas' => 0,
                'is_menu' => 0,
                'status' => 1,
                'menu_status' => 0,
                'relate_to_child' => 0,
                'alternate_module' => null,
                'permission_section'    => 0,
                'section_id' => 1,
                'user_id' => null,
                'type' => 3,
                'old_id' => null,
                'child' => array(),
            ),
        );
        foreach ($permissions as $newsPermission) {
            storePermissionData($newsPermission);
        }

        $dormitory_transport_exists = Permission::where('route', 'student_transport_report_index')->first();
        if($dormitory_transport_exists){
            $dormitory_transport_exists->route = "student_transport_report";
            $dormitory_transport_exists->update();
        }
    
        $dormitory_report_exists = Permission::where('route', 'student_dormitory_report_index')->first();
        if($dormitory_report_exists){
            $dormitory_report_exists->route = "student_dormitory_report";
            $dormitory_report_exists->update();
        }

        Schema::table('exam_merit_positions', function (Blueprint $table) {
            if(Schema::hasColumn('exam_merit_positions', 'total_mark')){
                $table->double('total_mark',20,2)->change();
            }
        });

        $generalSettings = SmGeneralSettings::first();
        if ($generalSettings) {
            $generalSettings->software_version = '8.2.1';
            $generalSettings->update();
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
