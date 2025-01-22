<?php

use App\SmGeneralSettings;
use App\InfixModuleManager;
use App\SmHeaderMenuManager;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $exist = InfixModuleManager::where('name', 'ToyyibPay')->first();
        if (!$exist) {
            $name = 'ToyyibPay';
            $s = new InfixModuleManager();
            $s->name = $name;
            $s->email = 'support@spondonit.com';
            $s->notes = "This is ToyyibPay module for Online payemnt. Thanks for using.";
            $s->version = "1.0";
            $s->update_url = "https://spondonit.com/contact";
            $s->is_default = 0;
            $s->addon_url = "https://codecanyon.net/item/infixedu-zoom-live-class/27623128?s_rank=12";
            $s->installed_domain = url('/');
            $s->activated_date = date('Y-m-d');
            $s->save();
        }

        $generalSettings = SmGeneralSettings::first();
        if ($generalSettings) {
            $generalSettings->software_version = '8.2.0';
            $generalSettings->update();
        }

        $permissions =  array(
            'fees_collect_student_wise' => array(
                'module' => null,
                'sidebar_menu' => 'system_settings',
                'name' => 'Fees Collect Student Wise',
                'lang_name' => 'Fees Collect Student Wise',
                'icon' => null,
                'svg' => null,
                'route' => 'fees_collect_student_wise',
                'parent_route' => 'collect_fees',
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
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
