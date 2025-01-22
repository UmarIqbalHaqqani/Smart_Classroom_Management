<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Modules\RolePermission\Entities\Permission;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (moduleStatusCheck('CustomMenu')) {
            $cms= \Modules\CustomMenu\Entities\CustomMenu::all();
            foreach($cms as $cm){
                $roles  = json_decode($cm->available_for, true);
    
                if($is_student = in_array(2, $roles)){
                    unset($roles[2]);
    
                    $permission = Permission::where('custom_menu_id', $cm->id)->where('is_student', 1)->first();
                    if ($permission) {
                        $permission->update([
                            'route' => $cm->slug,
                            'name' => $cm->title,
                            'icon' => $cm->icon,
                        ]);
                    } else {
                        Permission::create([
                            'module' => 'CustomMenu',
                            'sidebar_menu' => 'custom_menu',
                            'route' => $cm->slug,
                            'name' => $cm->title,
                            'type' => 2,
                            'icon' => $cm->icon,
                            'is_menu' => 1,
                            'custom_menu_id' => $cm->id,
                            'permission_section' => 0,
                            'is_admin' => 0,
                            'is_student' => $is_student,
                            'is_parent' => 0,
                        ]);
                    }
    
                } else{
                    Permission::where('custom_menu_id', $cm->id)->where('is_student', 1)->delete();
    
                }
    
                if($is_parent = in_array(3, $roles)){
                    unset($roles[3]);
                    $permission = Permission::where('custom_menu_id', $cm->id)->where('is_parent', 1)->first();
                    if ($permission) {
                        $permission->update([
                            'route' => $cm->slug,
                            'name' => $cm->title,
                            'icon' => $cm->icon,
                        ]);
                    } else {
                        Permission::create([
                            'module' => 'CustomMenu',
                            'sidebar_menu' => 'custom_menu',
                            'route' => $cm->slug,
                            'name' => $cm->title,
                            'type' => 2,
                            'icon' => $cm->icon,
                            'is_menu' => 1,
                            'custom_menu_id' => $cm->id,
                            'permission_section' => 0,
                            'is_admin' => 0,
                            'is_student' => 0,
                            'is_parent' => $is_parent,
                        ]);
                    }
                } else{
                    Permission::where('custom_menu_id', $cm->id)->where('is_parent', 1)->delete();
                }
    
                if(count($roles) > 0){
                    $permission = Permission::where('custom_menu_id', $cm->id)->where('is_admin', 1)->first();
                    if ($permission) {
                        $permission->update([
                            'route' => $cm->slug,
                            'name' => $cm->title,
                            'icon' => $cm->icon,
                        ]);
                    } else {
                        Permission::create([
                            'module' => 'CustomMenu',
                            'sidebar_menu' => 'custom_menu',
                            'route' => $cm->slug,
                            'name' => $cm->title,
                            'type' => 2,
                            'icon' => $cm->icon,
                            'is_menu' => 1,
                            'custom_menu_id' => $cm->id,
                            'permission_section' => 0,
                            'is_admin' => 1,
                            'is_student' => 0,
                            'is_parent' => 0,
                        ]);
                    }
                } else{
                    Permission::where('custom_menu_id', $cm->id)->where('is_admin', 1)->delete();
                }
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('permission', function (Blueprint $table) {
            //
        });
    }
};
