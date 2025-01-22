<?php

use App\SmGeneralSettings;
use Illuminate\Database\Migrations\Migration;


return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $permissions =  array(
            'cron-job' => array(
                'module' => null,
                'sidebar_menu' => 'system_settings',
                'name' => 'Language Settings',
                'lang_name' => 'system_settings.cron_job',
                'icon' => null,
                'svg' => null,
                'route' => 'cron-job',
                'parent_route' => 'general_settings',
                'is_admin' => 1,
                'is_teacher' => 0,
                'is_student' => 0,
                'is_parent' => 0,
                'position' => 8,
                'is_saas' => 0,
                'is_menu' => 1,
                'status' => 1,
                'menu_status' => 1,
                'relate_to_child' => 0,
                'alternate_module' => null,
                'permission_section' => 0,
                'user_id' => null,
                'type' => 2,
                'old_id' => 451,
                'child' => array(),
            ),
        );

        foreach ($permissions as $data) {
            storePermissionData($data);
        }

        $generalSettings = SmGeneralSettings::first();
        if ($generalSettings) {
            $generalSettings->software_version = '8.2.7';
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
