<?php


use App\SmGeneralSettings;
use App\InfixModuleManager;
use App\SmHeaderMenuManager;
use Illuminate\Support\Facades\Schema;
use Larabuild\Pagebuilder\Models\Page;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Modules\RolePermission\Entities\Permission;

return new class extends Migration
{
    public function up()
    {
        return true;
        $data = [
            'apply-leave' => array(
                'module' => null,
                'sidebar_menu' => 'leave',
                'name' => 'Apply Leave',
                'lang_name' => 'leave.apply_leave',
                'icon' => null,
                'svg' => null,
                'route' => 'apply-leave',
                'parent_route' => 'leave',
                'is_admin' => 1,
                'is_teacher' => 1,
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
                'old_id' => 189,
                'child' => array(
                    'apply-leave-store' => array(
                        'module' => null,
                        'sidebar_menu' => null,
                        'name' => 'Add',
                        'lang_name' => null,
                        'icon' => null,
                        'svg' => null,
                        'route' => 'apply-leave-store',
                        'parent_route' => 'apply-leave',
                        'is_admin' => 1,
                        'is_teacher' => 0,
                        'is_student' => 0,
                        'is_parent' => 0,
                        'position' => 191,
                        'is_saas' => 0,
                        'is_menu' => 0,
                        'status' => 1,
                        'menu_status' => 1,
                        'relate_to_child' => 0,
                        'alternate_module' => null,
                        'permission_section' => 0,
                        'user_id' => null,
                        'type' => 3,
                        'old_id' => 190,
                    ),
                    'apply-leave-edit' => array(
                        'module' => null,
                        'sidebar_menu' => null,
                        'name' => 'Edit',
                        'lang_name' => null,
                        'icon' => null,
                        'svg' => null,
                        'route' => 'apply-leave-edit',
                        'parent_route' => 'apply-leave',
                        'is_admin' => 1,
                        'is_teacher' => 0,
                        'is_student' => 0,
                        'is_parent' => 0,
                        'position' => 191,
                        'is_saas' => 0,
                        'is_menu' => 0,
                        'status' => 1,
                        'menu_status' => 1,
                        'relate_to_child' => 0,
                        'alternate_module' => null,
                        'permission_section' => 0,
                        'user_id' => null,
                        'type' => 3,
                        'old_id' => 190,
                    ),
                    'apply-leave-delete' => array(
                        'module' => null,
                        'sidebar_menu' => null,
                        'name' => 'Delete',
                        'lang_name' => null,
                        'icon' => null,
                        'svg' => null,
                        'route' => 'apply-leave-delete',
                        'parent_route' => 'apply-leave',
                        'is_admin' => 1,
                        'is_teacher' => 0,
                        'is_student' => 0,
                        'is_parent' => 0,
                        'position' => 191,
                        'is_saas' => 0,
                        'is_menu' => 0,
                        'status' => 1,
                        'menu_status' => 1,
                        'relate_to_child' => 0,
                        'alternate_module' => null,
                        'permission_section' => 0,
                        'user_id' => null,
                        'type' => 3,
                        'old_id' => 190,
                    ),
                ),
            ),
        ];

        $themes = [
            'admin-theme' => array(
                'module' => null,
                'sidebar_menu' => 'front_setting',
                'name' => 'Manage Theme',
                'lang_name' => 'front_settings.Manage Theme',
                'icon' => null,
                'svg' => null,
                'route' => 'theme.index',
                'parent_route' => 'frontend_cms',
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
                'old_id' => 493,
                'child' => array(
                    'admin-theme-upload' => array(
                        'module' => null,
                        'sidebar_menu' => null,
                        'name' => 'Upload Theme',
                        'lang_name' => null,
                        'icon' => null,
                        'svg' => null,
                        'route' => 'theme.upload',
                        'parent_route' => 'theme.index',
                        'is_admin' => 1,
                        'is_teacher' => 0,
                        'is_student' => 0,
                        'is_parent' => 0,
                        'position' => 495,
                        'is_saas' => 0,
                        'is_menu' => 0,
                        'status' => 1,
                        'menu_status' => 1,
                        'relate_to_child' => 0,
                        'alternate_module' => null,
                        'permission_section' => 0,
                        'user_id' => null,
                        'type' => 3,
                        'old_id' => 494,
                    ),
                    'admin-theme-install' => array(
                        'module' => null,
                        'sidebar_menu' => null,
                        'name' => 'Install Theme',
                        'lang_name' => null,
                        'icon' => null,
                        'svg' => null,
                        'route' => 'theme.install',
                        'parent_route' => 'theme.index',
                        'is_admin' => 1,
                        'is_teacher' => 0,
                        'is_student' => 0,
                        'is_parent' => 0,
                        'position' => 495,
                        'is_saas' => 0,
                        'is_menu' => 0,
                        'status' => 1,
                        'menu_status' => 1,
                        'relate_to_child' => 0,
                        'alternate_module' => null,
                        'permission_section' => 0,
                        'user_id' => null,
                        'type' => 3,
                        'old_id' => 494,
                    ),
                    'admin-theme-remove' => array(
                        'module' => null,
                        'sidebar_menu' => null,
                        'name' => 'Remove Theme',
                        'lang_name' => null,
                        'icon' => null,
                        'svg' => null,
                        'route' => 'theme.remove',
                        'parent_route' => 'theme.index',
                        'is_admin' => 1,
                        'is_teacher' => 0,
                        'is_student' => 0,
                        'is_parent' => 0,
                        'position' => 495,
                        'is_saas' => 0,
                        'is_menu' => 0,
                        'status' => 1,
                        'menu_status' => 1,
                        'relate_to_child' => 0,
                        'alternate_module' => null,
                        'permission_section' => 0,
                        'user_id' => null,
                        'type' => 3,
                        'old_id' => 494,
                    ),
                ),
            ),
            'home-slider' => array(
                'module' => null,
                'sidebar_menu' => 'front_setting',
                'name' => 'Home Slider',
                'lang_name' => 'front_settings.Home Slider',
                'icon' => null,
                'svg' => null,
                'route' => 'home-slider',
                'parent_route' => 'frontend_cms',
                'is_admin' => 1,
                'is_teacher' => 0,
                'is_student' => 0,
                'is_parent' => 0,
                'position' => 2,
                'is_saas' => 0,
                'is_menu' => 1,
                'status' => 0,
                'menu_status' => 0,
                'relate_to_child' => 0,
                'alternate_module' => null,
                'permission_section' => 0,
                'user_id' => null,
                'type' => 2,
                'old_id' => 493,
                'child' => array(),
            ),
            'Aora_Pagebuilder' => array(
                'module' => null,
                'sidebar_menu' => 'front_setting',
                'name' => 'Aora Pagebuilder',
                'lang_name' => 'front_settings.Aora Pagebuilder',
                'icon' => null,
                'svg' => null,
                'route' => 'pagebuilder',
                'parent_route' => 'frontend_cms',
                'is_admin' => 1,
                'is_teacher' => 0,
                'is_student' => 0,
                'is_parent' => 0,
                'position' => 2,
                'is_saas' => 0,
                'is_menu' => 1,
                'status' => 0,
                'menu_status' => 0,
                'relate_to_child' => 0,
                'alternate_module' => null,
                'permission_section' => 0,
                'user_id' => null,
                'type' => 2,
                'old_id' => 493,
                'child' => array(),
            ),
            'expert_teacher' => array(
                'module' => null,
                'sidebar_menu' => 'front_setting',
                'name' => 'Expert Teacher',
                'lang_name' => 'front_settings.Expert Teacher',
                'icon' => null,
                'svg' => null,
                'route' => 'expert-teacher',
                'parent_route' => 'frontend_cms',
                'is_admin' => 1,
                'is_teacher' => 0,
                'is_student' => 0,
                'is_parent' => 0,
                'position' => 2,
                'is_saas' => 0,
                'is_menu' => 1,
                'status' => 0,
                'menu_status' => 0,
                'relate_to_child' => 0,
                'alternate_module' => null,
                'permission_section' => 0,
                'user_id' => null,
                'type' => 2,
                'old_id' => 493,
                'child' => array(),
            ),
            'photo-gallery' => array(
                'module' => null,
                'sidebar_menu' => 'front_setting',
                'name' => 'Photo Gallery',
                'lang_name' => 'front_settings.Photo Gallery',
                'icon' => null,
                'svg' => null,
                'route' => 'photo-gallery',
                'parent_route' => 'frontend_cms',
                'is_admin' => 1,
                'is_teacher' => 0,
                'is_student' => 0,
                'is_parent' => 0,
                'position' => 3,
                'is_saas' => 0,
                'is_menu' => 1,
                'status' => 0,
                'menu_status' => 0,
                'relate_to_child' => 0,
                'alternate_module' => null,
                'permission_section' => 0,
                'user_id' => null,
                'type' => 2,
                'old_id' => 493,
                'child' => array(),
            ),
            'video-gallery' => array(
                'module' => null,
                'sidebar_menu' => 'front_setting',
                'name' => 'Video Gallery',
                'lang_name' => 'front_settings.Video Gallery',
                'icon' => null,
                'svg' => null,
                'route' => 'video-gallery',
                'parent_route' => 'frontend_cms',
                'is_admin' => 1,
                'is_teacher' => 0,
                'is_student' => 0,
                'is_parent' => 0,
                'position' => 4,
                'is_saas' => 0,
                'is_menu' => 1,
                'status' => 0,
                'menu_status' => 0,
                'relate_to_child' => 0,
                'alternate_module' => null,
                'permission_section' => 0,
                'user_id' => null,
                'type' => 2,
                'old_id' => 493,
                'child' => array(),
            ),
            'front-result' => array(
                'module' => null,
                'sidebar_menu' => 'front_setting',
                'name' => 'Result',
                'lang_name' => 'front_settings.Result',
                'icon' => null,
                'svg' => null,
                'route' => 'front-result',
                'parent_route' => 'frontend_cms',
                'is_admin' => 1,
                'is_teacher' => 0,
                'is_student' => 0,
                'is_parent' => 0,
                'position' => 5,
                'is_saas' => 0,
                'is_menu' => 1,
                'status' => 0,
                'menu_status' => 0,
                'relate_to_child' => 0,
                'alternate_module' => null,
                'permission_section' => 0,
                'user_id' => null,
                'type' => 2,
                'old_id' => 493,
                'child' => array(),
            ),
            'front-class-routine' => array(
                'module' => null,
                'sidebar_menu' => 'front_setting',
                'name' => 'Front Result',
                'lang_name' => 'front_settings.Class Routine',
                'icon' => null,
                'svg' => null,
                'route' => 'front-class-routine',
                'parent_route' => 'frontend_cms',
                'is_admin' => 1,
                'is_teacher' => 0,
                'is_student' => 0,
                'is_parent' => 0,
                'position' => 6,
                'is_saas' => 0,
                'is_menu' => 1,
                'status' => 0,
                'menu_status' => 0,
                'relate_to_child' => 0,
                'alternate_module' => null,
                'permission_section' => 0,
                'user_id' => null,
                'type' => 2,
                'old_id' => 493,
                'child' => array(),
            ),
            'front-exam-routine' => array(
                'module' => null,
                'sidebar_menu' => 'front_setting',
                'name' => 'Exam Routine',
                'lang_name' => 'front_settings.Exam Routine',
                'icon' => null,
                'svg' => null,
                'route' => 'front-exam-routine',
                'parent_route' => 'frontend_cms',
                'is_admin' => 1,
                'is_teacher' => 0,
                'is_student' => 0,
                'is_parent' => 0,
                'position' => 7,
                'is_saas' => 0,
                'is_menu' => 1,
                'status' => 0,
                'menu_status' => 0,
                'relate_to_child' => 0,
                'alternate_module' => null,
                'permission_section' => 0,
                'user_id' => null,
                'type' => 2,
                'old_id' => 493,
                'child' => array(),
            ),
            'front-academic-calendar' => array(
                'module' => null,
                'sidebar_menu' => 'front_setting',
                'name' => 'Academic Calendar',
                'lang_name' => 'front_settings.Academic Calendar',
                'icon' => null,
                'svg' => null,
                'route' => 'front-academic-calendar',
                'parent_route' => 'frontend_cms',
                'is_admin' => 1,
                'is_teacher' => 0,
                'is_student' => 0,
                'is_parent' => 0,
                'position' => 8,
                'is_saas' => 0,
                'is_menu' => 1,
                'status' => 0,
                'menu_status' => 0,
                'relate_to_child' => 0,
                'alternate_module' => null,
                'permission_section' => 0,
                'user_id' => null,
                'type' => 2,
                'old_id' => 493,
                'child' => array(),
            ),
            'front-header-content' => array(
                'module' => null,
                'sidebar_menu' => 'front_setting',
                'name' => 'Header Content',
                'lang_name' => 'front_settings.Header Content',
                'icon' => null,
                'svg' => null,
                'route' => 'pagebuilder.header',
                'parent_route' => 'frontend_cms',
                'is_admin' => 1,
                'is_teacher' => 0,
                'is_student' => 0,
                'is_parent' => 0,
                'position' => 8,
                'is_saas' => 0,
                'is_menu' => 1,
                'status' => 0,
                'menu_status' => 0,
                'relate_to_child' => 0,
                'alternate_module' => null,
                'permission_section' => 0,
                'user_id' => null,
                'type' => 2,
                'old_id' => 493,
                'child' => array(),
            ),
            'front-footer-content' => array(
                'module' => null,
                'sidebar_menu' => 'front_setting',
                'name' => 'Footer Content',
                'lang_name' => 'front_settings.Footer Content',
                'icon' => null,
                'svg' => null,
                'route' => 'pagebuilder.footer',
                'parent_route' => 'frontend_cms',
                'is_admin' => 1,
                'is_teacher' => 0,
                'is_student' => 0,
                'is_parent' => 0,
                'position' => 8,
                'is_saas' => 0,
                'is_menu' => 1,
                'status' => 0,
                'menu_status' => 0,
                'relate_to_child' => 0,
                'alternate_module' => null,
                'permission_section' => 0,
                'user_id' => null,
                'type' => 2,
                'old_id' => 493,
                'child' => array(),
            )
        ];

        foreach ($themes as $theme) {
            storePermissionData($theme);
        }

        Schema::table('sm_testimonials', function (Blueprint $table) {
            if (!Schema::hasColumn('sm_testimonials', 'star_rating')) {
                $table->float('star_rating')->default(5)->nullable();
            }
        });


        Schema::table('student_bulk_temporaries', function (Blueprint $table) {
            if (!Schema::hasColumn('student_bulk_temporaries', 'note')) {
                $table->text('note')->nullable()->change();
                //column type varchar change to text
            }
        });

        Schema::table('sm_students', function (Blueprint $table) {
            if (!Schema::hasColumn('sm_students', 'aditional_notes')) {
                $table->text('aditional_notes')->nullable()->change();
                //column type varchar change to text
            }
        });

        Schema::table('sm_news', function (Blueprint $table) {
            if (!Schema::hasColumn('sm_news', 'status')) {
                $table->tinyInteger('status')->default(1)->nullable();
            }
            if (!Schema::hasColumn('sm_news', 'is_global')) {
                $table->tinyInteger('is_global')->default(1)->nullable();
            }
            if (!Schema::hasColumn('sm_news', 'auto_approve')) {
                $table->tinyInteger('auto_approve')->default(0)->nullable();
            }
            if (!Schema::hasColumn('sm_news', 'is_comment')) {
                $table->tinyInteger('is_comment')->default(0)->nullable();
            }
        });

        Schema::table('sm_general_settings', function (Blueprint $table) {
            if (!Schema::hasColumn('sm_general_settings', 'auto_approve')) {
                $table->tinyInteger('auto_approve')->default(1)->nullable();
            }
            if (!Schema::hasColumn('sm_general_settings', 'is_comment')) {
                $table->tinyInteger('is_comment')->default(1)->nullable();
            }
            if (!Schema::hasColumn('sm_general_settings', 'AiContent')) {
                $table->integer('AiContent')->default(0)->nullable();
            }
            if (!Schema::hasColumn('sm_general_settings', 'WhatsappSupport')) {
                $table->integer('WhatsappSupport')->default(0)->nullable();
            }
            if (!Schema::hasColumn('sm_general_settings', 'DownloadCenter')) {
                $table->integer('DownloadCenter')->default(0)->nullable();
            }
            if (!Schema::hasColumn('sm_general_settings', 'active_theme')) {
                $table->string('active_theme')->nullable();
            }
        });

        //DownloadCenter 
        $exist = InfixModuleManager::where('name','DownloadCenter')->first();
        if(!$exist){
            $s = new InfixModuleManager();
            $s->name = "DownloadCenter";
            $s->email = 'support@spondonit.com';
            $s->notes = "This Module is named Download Center for managing study materials more efficiently. Thanks for using.";
            $s->version = "1.0";
            $s->update_url = "https://spondonit.com/contact";
            $s->is_default = 0;
            $s->purchase_code = time();
            $s->addon_url = "mailto:support@spondonit.com";
            $s->installed_domain = url('/');
            $s->activated_date = date('Y-m-d');
            $s->save();

            $controller = new \App\Http\Controllers\Admin\SystemSettings\SmAddOnsController();
            $controller->FreemoduleAddOnsEnable("DownloadCenter");
        }
        
        //AiContent 
        $exist2 = InfixModuleManager::where('name','AiContent')->first();
        if(!$exist2){
            $s2 = new InfixModuleManager();
            $s2->name = "AiContent";
            $s2->email = 'support@spondonit.com';
            $s2->notes = "This is AI Content Generator module. Generate content via AI.";
            $s2->version = "1.0";
            $s2->update_url = "https://spondonit.com/contact";
            $s2->is_default = 0;
            $s2->addon_url = "maito:support@spondonit.com";
            $s2->installed_domain = url('/');
            $s2->activated_date = date('Y-m-d');
            $s2->save();
        }
        

        //WhatsappSupport 
        $exist3 = InfixModuleManager::where('name','WhatsappSupport')->first();
        if(!$exist3){
            $s3 = new InfixModuleManager();
            $s3->name = "WhatsappSupport";
            $s3->email = 'support@spondonit.com';
            $s3->notes = "This is WhatsApp Support module. Send message via WhatsApp.";
            $s3->version = "1.0";
            $s3->update_url = "https://spondonit.com/contact";
            $s3->is_default = 0;
            $s3->addon_url = "maito:support@spondonit.com";
            $s3->installed_domain = url('/');
            $s3->activated_date = date('Y-m-d');
            $s3->save();
        }
        
        $newsPermissions = [
            'news-comment-list' => array(
                'module' => null,
                'sidebar_menu' => 'front_setting',
                'name' => 'News Comments',
                'lang_name' => 'front_settings.news_comments',
                'icon' => null,
                'svg' => null,
                'route' => 'news-comment-list',
                'parent_route' => 'frontend_cms',
                'is_admin' => 1,
                'is_teacher' => 0,
                'is_student' => 0,
                'is_parent' => 0,
                'position' => 5,
                'is_saas' => 0,
                'is_menu' => 1,
                'status' => 0,
                'menu_status' => 0,
                'relate_to_child' => 0,
                'alternate_module' => null,
                'permission_section' => 0,
                'user_id' => null,
                'type' => 2,
                'old_id' => 495,
                'child' => array(
                    'news-comment-status' => array(
                        'module' => null,
                        'sidebar_menu' => null,
                        'name' => 'Change News Status',
                        'lang_name' => null,
                        'icon' => null,
                        'svg' => null,
                        'route' => 'news-comment-status',
                        'parent_route' => 'news-comment-list',
                        'is_admin' => 1,
                        'is_teacher' => 0,
                        'is_student' => 0,
                        'is_parent' => 0,
                        'position' => 498,
                        'is_saas' => 0,
                        'is_menu' => 0,
                        'status' => 1,
                        'menu_status' => 1,
                        'relate_to_child' => 0,
                        'alternate_module' => null,
                        'permission_section' => 0,
                        'user_id' => null,
                        'type' => 3,
                        'old_id' => 497,
                    ),
                    'news-comment-delete' => array(
                        'module' => null,
                        'sidebar_menu' => null,
                        'name' => 'Delete Comment',
                        'lang_name' => null,
                        'icon' => null,
                        'svg' => null,
                        'route' => 'news-comment-delete',
                        'parent_route' => 'news-comment-list',
                        'is_admin' => 1,
                        'is_teacher' => 0,
                        'is_student' => 0,
                        'is_parent' => 0,
                        'position' => 498,
                        'is_saas' => 0,
                        'is_menu' => 0,
                        'status' => 1,
                        'menu_status' => 1,
                        'relate_to_child' => 0,
                        'alternate_module' => null,
                        'permission_section' => 0,
                        'user_id' => null,
                        'type' => 3,
                        'old_id' => 497,
                    ),
                ),
            ),
        ];

        foreach ($newsPermissions as $newsPermission) {
            storePermissionData($newsPermission);
        }

        // Header Menu Manage Start
            Schema::table('sm_header_menu_managers', function (Blueprint $table) {
                if (!Schema::hasColumn('sm_header_menu_managers', 'theme')) {
                    $table->string('theme')->default('default');
                }
            });
        // Header Menu Manage End

        Schema::table('sm_general_settings', function (Blueprint $table) {
            if (!Schema::hasColumn('sm_general_settings', 'blog_search')) {
                $table->tinyInteger('blog_search')->default(1)->nullable();
            }
            if (!Schema::hasColumn('sm_general_settings', 'recent_blog')) {
                $table->tinyInteger('recent_blog')->default(1)->nullable();
            }
        });

        $edulia_routes = ['home-slider','expert-teacher','photo-gallery','video-gallery','front-result','front-class-routine','front-exam-routine','front-academic-calendar','news-comment-list','pagebuilder'];
        $status = 0;
        Permission::where('school_id', 1)
            ->whereIn('route', $edulia_routes)->update(['status' => $status, 'menu_status' => $status]);
    }

    public function down(): void
    {
        //
    }
};
