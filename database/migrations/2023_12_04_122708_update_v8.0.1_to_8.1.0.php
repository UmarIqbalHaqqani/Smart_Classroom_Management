<?php

use App\Models\ColorTheme;
use App\InfixModuleManager;
use App\SmHeaderMenuManager;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use App\Models\SmNotificationSetting;
use Illuminate\Support\Facades\Schema;
use Larabuild\Pagebuilder\Models\Page;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Modules\RolePermission\Entities\Permission;
use Modules\ParentRegistration\Entities\SmStudentField;

return new class extends Migration
{

    function replace_array_recursive(string $needle, string $replace, array &$haystack)
    {
        array_walk_recursive(
            $haystack,
            function (&$item, $key, $data) {
                $item = str_replace($data['needle'], $data['replace'], $item);
                return $item;
            },
            ['needle' => $needle, 'replace' => $replace]
        );
    }

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (!Schema::hasColumn('sm_student_certificates', 'layout')) {
            Schema::table('sm_student_certificates', function (Blueprint $table) {
                $table->integer('layout')->nullable()->comment('1 = Portrait, 2 =  Landscape');
            });
        }
        if (!Schema::hasColumn('sm_student_certificates', 'body_font_family')) {
            Schema::table('sm_student_certificates', function (Blueprint $table) {
                $table->string('body_font_family')->nullable()->default('Arial')->comment('body_font_family');
            });
        }
        if (!Schema::hasColumn('sm_student_certificates', 'body_font_size')) {
            Schema::table('sm_student_certificates', function (Blueprint $table) {
                $table->string('body_font_size')->nullable()->default('2em')->comment('');
            });
        }

        if (!Schema::hasColumn('sm_student_certificates', 'height')) {
            Schema::table('sm_student_certificates', function (Blueprint $table) {
                $table->string('height', 50)->nullable()->comment('Height in mm');
            });
        }

        if (!Schema::hasColumn('sm_student_certificates', 'width')) {
            Schema::table('sm_student_certificates', function (Blueprint $table) {
                $table->string('width', 50)->nullable()->comment('width in mm');
            });
        }
        if (!Schema::hasColumn('sm_student_certificates', 'default_for')) {
            Schema::table('sm_student_certificates', function (Blueprint $table) {
                $table->string('default_for', 50)->nullable()->comment('default_for course');
            });
        }


        // Datatable Row Position Start
        Schema::table('sm_video_galleries', function (Blueprint $table) {
            if (!Schema::hasColumn('sm_video_galleries', 'position')) {
                $table->integer('position')->default(0);
            }
        });

        Schema::table('sm_photo_galleries', function (Blueprint $table) {
            if (!Schema::hasColumn('sm_photo_galleries', 'position')) {
                $table->integer('position')->default(0);
            }
        });

        Schema::table('infixedu__pages', function (Blueprint $table) {
            if (!Schema::hasColumn('infixedu__pages', 'is_default')) {
                $table->boolean('is_default')->default(0);
            }
        });

        Schema::table('sm_expert_teachers', function (Blueprint $table) {
            if (!Schema::hasColumn('sm_expert_teachers', 'position')) {
                $table->integer('position')->default(0);
            }
            if (!Schema::hasColumn('sm_expert_teachers', 'staff_id')) {
                $table->tinyInteger('staff_id');
            }
            if (!Schema::hasColumn('sm_expert_teachers', 'created_by')) {
                $table->tinyInteger('created_by')->nullable();
            }
            if (!Schema::hasColumn('sm_expert_teachers', 'updated_by')) {
                $table->tinyInteger('updated_by')->nullable();
            }
            if (Schema::hasColumn('sm_expert_teachers', 'name')) {
                $table->dropColumn('name');
            }
            if (Schema::hasColumn('sm_expert_teachers', 'designation')) {
                $table->dropColumn('designation');
            }
            if (Schema::hasColumn('sm_expert_teachers', 'image')) {
                $table->dropColumn('image');
            }
        });

        DB::table('sm_expert_teachers')->truncate();

        Schema::table('sm_contact_messages', function (Blueprint $table) {
            if (!Schema::hasColumn('sm_contact_messages', 'phone')) {
                $table->string('phone')->nullable();
            }
        });
        
        Schema::table('sm_staffs', function (Blueprint $table) {
            if (!Schema::hasColumn('sm_staffs', 'show_public')) {
                $table->tinyInteger('show_public')->default(0);
            }
        });


        $permissions =  array(
                'plugin-tawk-setting' => array(
                    'module' => null,
                    'sidebar_menu' => 'system_settings',
                    'name' => 'Tawk To Chat',
                    'lang_name' => 'system_settings.Tawk To Chat',
                    'icon' => null,
                    'svg' => null,
                    'route' => 'tawkSetting',
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
                ),

                'plugin-messenger-setting' => array(
                    'module' => null,
                    'sidebar_menu' => 'system_settings',
                    'name' => 'Messenger Chat',
                    'lang_name' => 'system_settings.Messenger Chat',
                    'icon' => null,
                    'svg' => null,
                    'route' => 'messengerSetting',
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
                ),

                'speech-slider' => array(
                    'module' => null,
                    'sidebar_menu' => 'front_setting',
                    'name' => 'Speech Slider',
                    'lang_name' => 'front_settings.speech_slider',
                    'icon' => null,
                    'svg' => null,
                    'route' => 'speech-slider',
                    'parent_route' => 'frontend_cms',
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
                    'old_id' => 493,
                    'child' => array(),
                ),

                'donor' => array(
                    'module' => null,
                    'sidebar_menu' => 'front_setting',
                    'name' => 'Donor',
                    'lang_name' => 'front_settings.donor',
                    'icon' => null,
                    'svg' => null,
                    'route' => 'donor',
                    'parent_route' => 'frontend_cms',
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
                    'old_id' => 493,
                    'child' => array(),
                ),

                'form-download' => array(
                    'module' => null,
                    'sidebar_menu' => 'front_setting',
                    'name' => 'Form Download',
                    'lang_name' => 'front_settings.form_download',
                    'icon' => null,
                    'svg' => null,
                    'route' => 'form-download',
                    'parent_route' => 'frontend_cms',
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
                    'old_id' => 493,
                    'child' => array(),
                ),
            );

            foreach ($permissions as $newsPermission) {
                storePermissionData($newsPermission);
            }

        $s2 = InfixModuleManager::where('name', 'BehaviourRecords')->first();
        if($s2){
            $s2->is_default = 1;
            $s2->save();
        }

        $s = InfixModuleManager::where('name','InAppLiveClass')->whereNull('purchase_code')->count();
        if($s > 1){
            $del = InfixModuleManager::where('name','InAppLiveClass')->whereNull('purchase_code')->first();
            $del->delete();
        }

        

        


        // Datatable Row Position End

        // Permission Edit Start
        $studentTimelineExists = Permission::where('route', 'studentTimeline')->first();
        if($studentTimelineExists){
            $studentTimelineExists->name = "Record";
            $studentTimelineExists->update();
        }
        $studyMaterialExists = Permission::where('route', 'download_center')->first();
        if($studyMaterialExists){
            $studyMaterialExists->name = "Study Material";
            $studyMaterialExists->lang_name = "study.study_material";
            $studyMaterialExists->update();
        }
        $parentFeesExists = Permission::where('route', 'parent_fees')->first();
        if($parentFeesExists){
            $parentFeesExists->delete();
        }
        $walletExists = Permission::where('route', 'wallet.my-wallet')->where('is_student', 0)->where('is_parent', 1)->first();
        if($walletExists){
            $walletExists->delete();
        }
        $feesExists = Permission::where('route', '')->where('name', 'Fees')->where('is_admin', 0)->where('is_teacher', 0)->where('is_student', 0)->where('is_parent', 1)->where('old_id', 1157)->first();
        if($feesExists){
            $feesExists->delete();
        }

       // $in_app_live = Permission::where('module','InAppLiveClass')->delete();
        // Permission Edit End

        $pages = Page::whereIn('id',[1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17])->get();
            foreach($pages as $page){
                if($page){
                    $page->is_default = 1;
                    $page->update();
                }
            }
        $event = SmNotificationSetting::where('event', 'Student_Attendance')->first();
        if($event){
            $shortCodes = ["Student" => "[student_name], [class], [section], [attendance_type]",
            "Parent" => "[parent_name], [student_name], [class], [section], [attendance_type]"];
            $event->shortcode = $shortCodes;
            $event->update();
        }
        $event = SmNotificationSetting::where('event', 'Subject_Wise_Attendance')->first();
        if($event){
            $shortCodes = ["Student" => "[student_name], [subject], [attendance_type]",
            "Parent" => "[parent_name], [student_name], [subject], [attendance_type]",];
            $event->shortcode = $shortCodes;
            $event->update();
        }
        
        // Default color theme change start
        $colorThemes = ColorTheme::where('theme_id', 1)->delete();
        $themes = \App\Models\Theme::withOutGlobalScopes()->get();
        $sql = [];
        foreach($themes as $theme){
            if($theme->title == 'Default') { 
                $sql[] = ['theme_id'  => $theme->id, 'color_id' => 1, 'value'   => "#415094"];
                $sql[] = ['theme_id'  => $theme->id, 'color_id' => 2, 'value'  => "#7c32ff"];
                $sql[] = ['theme_id'  => $theme->id, 'color_id' => 3, 'value'  => "#7c32ff"];
                $sql[] = ['theme_id'  => $theme->id, 'color_id' => 4, 'value'  => "#7c32ff"];
                $sql[] = ['theme_id'  => $theme->id, 'color_id' => 5, 'value'  => "#828bb2"];
                $sql[] = ['theme_id'  => $theme->id, 'color_id' => 6, 'value'  => "#828bb2"];
                $sql[] = ['theme_id'  => $theme->id, 'color_id' => 7, 'value'  => "#ffffff"];
                $sql[] = ['theme_id'  => $theme->id, 'color_id' => 8, 'value'  => "#ffffff"];
                $sql[] = ['theme_id'  => $theme->id, 'color_id' => 9, 'value'  => "#000000"];
                $sql[] = ['theme_id'  => $theme->id, 'color_id' => 10, 'value' => "#000000"];
                $sql[] = ['theme_id'  => $theme->id, 'color_id' => 11, 'value' => "#EFF2F8"];
                $sql[] = ['theme_id'  => $theme->id, 'color_id' => 12, 'value' => "#ffffff"];
                $sql[] = ['theme_id'  => $theme->id, 'color_id' => 13, 'value' => "#51A351"];
                $sql[] = ['theme_id'  => $theme->id, 'color_id' => 14, 'value' => "#E09079"];
                $sql[] = ['theme_id'  => $theme->id, 'color_id' => 15, 'value' => "#FF6D68"];
                $sql[] = ['theme_id'  => $theme->id, 'color_id' => 16, 'value' => "#415094"];
                $sql[] = ['theme_id'  => $theme->id, 'color_id' => 17, 'value'  => "#222222"];
                $sql[] = ['theme_id'  => $theme->id, 'color_id' => 18, 'value'  => "#415094"];
                $sql[] = ['theme_id'  => $theme->id, 'color_id' => 19, 'value'  => "#0d0e12"];
                $sql[] = ['theme_id'  => $theme->id, 'color_id' => 20, 'value'  => "#ffffff"];
                $sql[] = ['theme_id'  => $theme->id, 'color_id' => 21, 'value'  => "#ffffff"];
                $sql[] = ['theme_id'  => $theme->id, 'color_id' => 29, 'value'  => "#415094"];
        
            }
        }
        DB::table('color_theme')->insert($sql);
        // Default color theme change end

        // Online registration change start
        if ((moduleStatusCheck('Lead') != true) && moduleStatusCheck('ParentRegistration')) {
            SmStudentField::where('field_name', 'phone_number')->where('school_id', app('school')->id)->update(['is_required' => 1]);
        }
        // Online registration change end

        // Teacher evaluation change start
        $teacherApprovedExists = Permission::where('route', 'teacher-approved-evaluation-report')->first();
        if($teacherApprovedExists){
            $teacherApprovedExists->name = "Approved Report";
            $teacherApprovedExists->lang_name = "teacherEvaluation.approved_report";
            $teacherApprovedExists->update();
        }
        $teacherPendingExists = Permission::where('route', 'teacher-pending-evaluation-report')->first();
        if($teacherPendingExists){
            $teacherPendingExists->name = "Pending Report";
            $teacherPendingExists->lang_name = "teacherEvaluation.pending_report";
            $teacherPendingExists->update();
        }
        $teacherWiseExists = Permission::where('route', 'teacher-wise-evaluation-report')->first();
        if($teacherWiseExists){
            $teacherWiseExists->name = "Teacher Wise Report";
            $teacherWiseExists->lang_name = "teacherEvaluation.teacher_wise_report";
            $teacherWiseExists->update();
        }
        // Teacher evaluation change end

        // Zoom Change Start
        $zoomExists = Permission::where('route', 'virtual_class')->first();
        if($zoomExists){
            $zoomExists->name = "Zoom";
            $zoomExists->lang_name = "common.zoom";
            $zoomExists->update();
        }
        // Zoom Change End



        $filesInFolder = File::files(resource_path('/views/themes/edulia/demo/'));
        foreach ($filesInFolder as $path) {
            $file = pathinfo($path);
            if (file_exists($file['dirname'] . '/' . $file['basename'])) {
                $file_content =  file_get_contents(($file['dirname'] . '/' . $file['basename']));
                $file_data = json_decode($file_content, true);
                $this->replace_array_recursive("[App_url]", (url('/')), $file_data);
                if ($file_data) {
                    $check_exist  = DB::table(config('pagebuilder.db_prefix', 'infixedu__') . 'pages')->where('school_id', 1)->where('slug', $file_data['slug'])->first();
                    if (!$check_exist) {
                        DB::table(config('pagebuilder.db_prefix', 'infixedu__') . 'pages')->insert(
                            [
                                'name' => $file_data['name'],
                                'title' => $file_data['title'],
                                'description' => $file_data['description'],
                                'slug' => $file_data['slug'],
                                'settings' => json_encode($file_data['settings']),
                                'home_page' => $file_data['home_page'],
                                'status' => 'published',
                                'is_default' => 1,
                                'school_id' => 1
                            ]
                        );
                    }
                }
            }
        }

        // function insertMenuManage($menu){
        //     $menuData = SmHeaderMenuManager::create($menu);
        //     if(gv($menu, 'childs')){
        //         foreach(gv($menu, 'childs') as $child){
        //             $child['parent_id'] = $menuData->id;
        //             insertMenuManage($child);
        //         }
        //     }
        // }

        $datas = 
          array (
              array (
                'type' => 'sPages',
                'element_id' => 22,
                'title' => 'Contact',
                'link' => '/contact-us',
                'position' => 7,
                'show' => 0,
                'is_newtab' => 0,
                'theme' => 'edulia',
                'school_id' => 1,
                'created_at' => '2024-01-05T07:43:22.000000Z',
                'updated_at' => '2024-01-05T07:43:22.000000Z',
                'childs' => 
                array (
                ),
              )
          );
        foreach($datas as $data){
            insertMenuManage($data);
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
