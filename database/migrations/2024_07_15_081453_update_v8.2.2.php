<?php

use App\SmGeneralSettings;
use App\InfixModuleManager;
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
            $generalSettings->software_version = '8.2.2';
            $generalSettings->update();
        }
        
        $routes = [
            'speech-slider-store',
            'speech-slider-edit',
            'speech-slider-update',
            'speech-slider-delete-modal',
            'speech-slider-delete',
        ];
        
        Permission::whereIn('route', $routes)
            ->where('parent_route', 'speech-slider')
            ->update(['menu_status' => 0]);

        $permissions = Permission::where(function ($query) {
            $query->where('route', 'invoice-settings')
                    ->where('parent_route', 'bulk_print')
                    ->where('type', 2)
                    ->where('school_id', 1);
        })->orWhere('route', 'invoice-settings')
            ->orWhere(function ($query) {
            $query->where('route', 'fees.fees-invoice-settings')
                    ->where('sidebar_menu', 'system_settings');
        })->get();
        
        foreach ($permissions as $permission) {
            $permission->delete();
        }
        
        Permission::where('route','invoice-settings')->where('parent_route','bulk_print')->where('type',2)->where('school_id',1)->delete();
        $permissions =  array(
            'fees.fees-invoice-settings' => array(
                'module' => null,
                'sidebar_menu' => 'system_settings',
                'name' => 'Fees Invoice Settings',
                'lang_name' => 'Fees Invoice Settings',
                'icon' => null,
                'svg' => null,
                'route' => 'fees.fees-invoice-settings',
                'parent_route' => 'fees_settings',
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
                'permission_section'    => 0,
                'section_id' => 1,
                'user_id' => null,
                'type' => 2,
                'old_id' => null,
                'child' => array(),
            ),

            'invoice-settings' => array(
                'module' => null,
                'sidebar_menu' => 'system_settings',
                'name' => 'Bulk Invoice Settings',
                'lang_name' => 'Bulk Invoice Settings',
                'icon' => null,
                'svg' => null,
                'route' => 'invoice-settings',
                'parent_route' => 'fees_settings',
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
                'old_id' => 1124,
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
