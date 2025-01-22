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
        $sql = [
            ['name'  => "base_color", 'default_value' => "#415094" , 'lawn_green' =>'#415094', 'is_color'=>1, 'status'=>1],
            ['name'  => "gradient_1", 'default_value' => "#7c32ff" , 'lawn_green' =>'#03e396', 'is_color'=>1, 'status'=>1],
            ['name'  => "gradient_2", 'default_value' => "#7c32ff" , 'lawn_green' =>'#03e396', 'is_color'=>1, 'status'=>1],
            ['name'  => "gradient_3", 'default_value' => "#7c32ff" , 'lawn_green' =>'#03e396', 'is_color'=>1, 'status'=>1],
    
            ['name'  => "scroll_color", 'default_value' => "#828bb2" , 'lawn_green' =>'#7e7172', 'is_color'=>1, 'status'=>1],
            ['name'  => "text-color", 'default_value' => "#828bb2" , 'lawn_green' =>'#828bb2', 'is_color'=>1, 'status'=>1],
            ['name'  => "text_white", 'default_value' => "#ffffff" , 'lawn_green' =>'#ffffff', 'is_color'=>1, 'status'=>1],
            ['name'  => "bg_white", 'default_value' => "#ffffff" , 'lawn_green' =>'#ffffff', 'is_color'=>1, 'status'=>1],
            ['name'  => "text_black", 'default_value' => "#000000" , 'lawn_green' =>'#000000', 'is_color'=>1, 'status'=>1],
            ['name'  => "bg_black", 'default_value' => "#000000" , 'lawn_green' =>'#000000', 'is_color'=>1, 'status'=>1],
            ['name'  => "border_color", 'default_value' => "#EFF2F8" , 'lawn_green' =>'#03e396', 'is_color'=>1, 'status'=>1],
            ['name'  => "input_bg", 'default_value' => "#FFFFFF" , 'lawn_green' =>'#ffffff', 'is_color'=>1, 'status'=>0],
    
            ['name'  => "success", 'default_value' => "#4BCF90" , 'lawn_green' =>'#51A351', 'is_color'=>1, 'status'=>1],
            ['name'  => "warning", 'default_value' => "#E09079" , 'lawn_green' =>'#E09079', 'is_color'=>1, 'status'=>1],
            ['name'  => "danger", 'default_value' => "#FF6D68" , 'lawn_green' =>'#FF6D68', 'is_color'=>1, 'status'=>1],
    
            ['name'  => "primary_color", 'default_value' => '#415094' , 'lawn_green' =>'#415094', 'is_color'=>1, 'status'=>0],
            ['name'  => "primary_color2", 'default_value' => '#222222' , 'lawn_green' =>'#222222', 'is_color'=>1, 'status'=>0],
            ['name'  => "title_color", 'default_value' => '#828bb2' , 'lawn_green' =>'#415094', 'is_color'=>1, 'status'=>1],
            ['name'  => "sidebar_bg", 'default_value' => '#0d0e12' , 'lawn_green' =>'#ffffff', 'is_color'=>1, 'status'=>1],
            ['name'  => "sidebar_active", 'default_value' => '#ffffff' , 'lawn_green' =>'#e7ecff', 'is_color'=>1, 'status'=>1],
            ['name'  => "sidebar_hover", 'default_value' => '#ffffff' , 'lawn_green' =>'#e7ecff', 'is_color'=>1, 'status'=>1],
            ['name'  => "barchart1", 'default_value' => '#8a33f8' , 'lawn_green' =>'#8a33f8', 'is_color'=>1, 'status'=>0],
            ['name'  => "barchart2", 'default_value' => '#f25278' , 'lawn_green' =>'#f25278', 'is_color'=>1, 'status'=>0],
            ['name'  => "barcharttextcolor", 'default_value' => '#415094' , 'lawn_green' =>'#415094', 'is_color'=>1, 'status'=>0],
            ['name'  => "barcharttextfamily", 'default_value' => '"poppins", sans-serif', 'lawn_green' =>'"poppins", sans-serif', 'is_color'=>0, 'status'=>0],
            ['name'  => "areachartlinecolor1", 'default_value' => 'rgba(124, 50, 255, 0.5)', 'lawn_green' =>'rgba(124, 50, 255, 0.5)', 'is_color'=>1, 'status'=>0],
            ['name'  => "areachartlinecolor2", 'default_value' => 'rgba(242, 82, 120, 0.5)', 'lawn_green' =>'rgba(242, 82, 120, 0.5)', 'is_color'=>1, 'status'=>0],
            ['name'  => "dashboardbackground", 'default_value' => '#FAFAFA', 'lawn_green' =>'', 'is_color'=>0, 'status'=>0],
            ['name'  => "box_shadow", 'default_value' => 'rgba(0, 0, 0, 0) 0px 0px 0px 0px, rgba(0, 0, 0, 0) 0px 0px 0px 0px, rgba(0, 0, 0, 0.1) 0px 1px 3px 0px, rgba(0, 0, 0, 0.1) 0px 1px 2px -1px', 'lawn_green' =>'', 'is_color'=>1, 'status'=>0],
            
            ['name'  => "primary-color", 'default_value' => '#7c32ff' , 'lawn_green' =>'', 'is_color'=>1, 'status'=>1],
    
            ['name'  => "sidebar-section", 'default_value' => '#636674' , 'lawn_green' =>'', 'is_color'=>1, 'status'=>1],
            ['name'  => "sidebar-nav-link", 'default_value' => '#9a9cae' , 'lawn_green' =>'', 'is_color'=>1, 'status'=>1],
            ['name'  => "transparent", 'default_value' => 'transparent' , 'lawn_green' =>'', 'is_color'=>1, 'status'=>1],
            ['name'  => "input_bg", 'default_value' => '#FFFFFF' , 'lawn_green' =>'', 'is_color'=>1, 'status'=>1],
            ['name'  => "red", 'default_value' => '#d33333' , 'lawn_green' =>'', 'is_color'=>1, 'status'=>1],
            ['name'  => "link-hover", 'default_value' => '#161931' , 'lawn_green' =>'', 'is_color'=>1, 'status'=>1],
            ['name'  => "notification_title", 'default_value' => 'rgb(14, 23, 38)' , 'lawn_green' =>'', 'is_color'=>1, 'status'=>1],
            ['name'  => "notification_time", 'default_value' => '#3b3f5c99' , 'lawn_green' =>'', 'is_color'=>1, 'status'=>1],
            ['name'  => "modalLink_color", 'default_value' => '#2f2f3be6' , 'lawn_green' =>'', 'is_color'=>1, 'status'=>1],
            ['name'  => "profile_text_hover", 'default_value' => '#2d3253' , 'lawn_green' =>'', 'is_color'=>1, 'status'=>1],
            ['name'  => "table_header", 'default_value' => 'rgb(246, 248, 250)' , 'lawn_green' =>'', 'is_color'=>1, 'status'=>1],
    
            ['name'  => "card-gradient-cyan_one", 'default_value' => '#06b6d4' , 'lawn_green' =>'', 'is_color'=>1, 'status'=>1],
            ['name'  => "card-gradient-cyan_two", 'default_value' => '#22d3ee' , 'lawn_green' =>'', 'is_color'=>1, 'status'=>1],
            ['name'  => "card-gradient-violet_one", 'default_value' => '#8b5cf6' , 'lawn_green' =>'', 'is_color'=>1, 'status'=>1],
            ['name'  => "card-gradient-violet_two", 'default_value' => '#a78bfa' , 'lawn_green' =>'', 'is_color'=>1, 'status'=>1],
            ['name'  => "card-gradient-blue_one", 'default_value' => '#3b82f6' , 'lawn_green' =>'', 'is_color'=>1, 'status'=>1],
            ['name'  => "card-gradient-blue_two", 'default_value' => '#60a5fa' , 'lawn_green' =>'', 'is_color'=>1, 'status'=>1],
            ['name'  => "card-gradient-fuchsia_one", 'default_value' => '#d946ef' , 'lawn_green' =>'', 'is_color'=>1, 'status'=>1],
            ['name'  => "card-gradient-fuchsia_two", 'default_value' => '#e879f9' , 'lawn_green' =>'', 'is_color'=>1, 'status'=>1],
    
            ['name'  => "card-gradient-cyan_one_hover", 'default_value' => '#06b6d4' , 'lawn_green' =>'', 'is_color'=>1, 'status'=>1],
            ['name'  => "card-gradient-cyan_two_hover", 'default_value' => '#22d3ee' , 'lawn_green' =>'', 'is_color'=>1, 'status'=>1],
            ['name'  => "card-gradient-violet_one_hover", 'default_value' => '#8b5cf6' , 'lawn_green' =>'', 'is_color'=>1, 'status'=>1],
            ['name'  => "card-gradient-violet_two_hover", 'default_value' => '#a78bfa' , 'lawn_green' =>'', 'is_color'=>1, 'status'=>1],
            ['name'  => "card-gradient-blue_one_hover", 'default_value' => '#3b82f6' , 'lawn_green' =>'', 'is_color'=>1, 'status'=>1],
            ['name'  => "card-gradient-blue_two_hover", 'default_value' => '#60a5fa' , 'lawn_green' =>'', 'is_color'=>1, 'status'=>1],
            ['name'  => "card-gradient-fuchsia_one_hover", 'default_value' => '#d946ef' , 'lawn_green' =>'', 'is_color'=>1, 'status'=>1],
            ['name'  => "card-gradient-fuchsia_two_hover", 'default_value' => '#e879f9' , 'lawn_green' =>'', 'is_color'=>1, 'status'=>1],
        ];
    
        $existingDatas = DB::table('colors')->pluck('name')->toArray();
    
        foreach ($sql as $color) {
            if (!in_array($color['name'], $existingDatas)) {
                $colorId = DB::table('colors')->insertGetId([
                    'name'          => $color['name'],
                    'default_value' => $color['default_value'],
                    'lawn_green'    => $color['lawn_green'],
                    'is_color'      => $color['is_color'],
                    'status'        => $color['status'],
                ]);
    
                $themes = DB::table('themes')->pluck('id');
                foreach ($themes as $themeId) {
                    DB::table('color_theme')->insert([
                        'color_id'      => $colorId,
                        'theme_id'      => $themeId,
                        'value'         => $color['default_value'],
                        'updated_at'    => now(),
                    ]);
                }
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
