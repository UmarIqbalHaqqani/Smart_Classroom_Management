<?php

use App\SmsTemplate;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up(): void
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

        $homeworkReportMenus = array(
            'homework-report' => array(
                'module' => null,
                'sidebar_menu' => 'homework',
                'name' => 'Homework Report',
                'lang_name' => 'homework.homework_report',
                'icon' => null,
                'svg' => null,
                'route' => 'homework-report',
                'parent_route' => 'homework',
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
                'old_id' => 284,
                'child' => array(
                    'view-homework-report' => array(
                        'module' => null,
                        'sidebar_menu' => null,
                        'name' => 'View',
                        'lang_name' => null,
                        'icon' => null,
                        'svg' => null,
                        'route' => 'view-homework-report',
                        'parent_route' => 'homework-report',
                        'is_admin' => 1,
                        'is_teacher' => 0,
                        'is_student' => 0,
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
                        'old_id' => 285,
                    ),
                    'homework-report-search' => array(
                        'module' => null,
                        'sidebar_menu' => null,
                        'name' => 'Search',
                        'lang_name' => null,
                        'icon' => null,
                        'svg' => null,
                        'route' => 'homework-report-search',
                        'parent_route' => 'homework-report',
                        'is_admin' => 1,
                        'is_teacher' => 0,
                        'is_student' => 0,
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
                        'old_id' => 285,
                    ),
                ),
            )
        );

        foreach ($calendarMenus as $menu) {
            storePermissionData($menu);
        }

        foreach ($studentParentMenus as $spMenu) {
            storePermissionData($spMenu);
        }

        foreach ($homeworkReportMenus as $hwrMenu) {
            storePermissionData($hwrMenu);
        }

        Schema::table('sm_events', function (Blueprint $table) {
            if (!Schema::hasColumn('sm_events', 'role_ids')) {
                $table->text('role_ids')->nullable();
            }
            if (!Schema::hasColumn('sm_events', 'url')) {
                $table->text('url')->nullable();
            }
        });

        $emailTemplates = [
            [
                'email', 'leave_applied', 'Leave Applied',
                '<table bgcolor="#FFFFFF" cellpadding="0" cellspacing="0" class="nl-container"
            style="table-layout:fixed;vertical-align:top;min-width:320px;border-spacing:0;border-collapse:collapse;background-color:#FFFFFF;width:100%;"
            width="100%">
            <tbody>
                <tr style="vertical-align:top;" valign="top">
                    <td style="vertical-align:top;" valign="top">
                        <div style="background-color:#415094;">
                            <div class="block-grid"
                                style="min-width:320px;max-width:600px;margin:0 auto;background-color:transparent;">
                                <div
                                    style="border-collapse:collapse;width:100%;background-color:transparent;background-position:center top;background-repeat:no-repeat;">
                                    <div class="col num12"
                                        style="min-width:320px;max-width:600px;vertical-align:top;width:600px;">
                                        <div class="col_cont" style="width:100%;">
                                            <div align="center" class="img-container center fixedwidth"
                                                style="padding-right:30px;padding-left:30px;">
                                                <a href="#">
                                                    <img border="0" class="center fixedwidth" src=""
                                                        style="padding-top:30px;padding-bottom:30px;text-decoration:none;height:auto;border:0;max-width:150px;"
                                                        width="150" alt="logo.png">
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div style="background-color:#415094;">
                            <div class="block-grid"
                                style="min-width:320px;max-width:600px;margin:0 auto;background-color:#ffffff;padding-top:25px;border-top-right-radius:30px;border-top-left-radius:30px;">
                                <div style="border-collapse:collapse;width:100%;background-color:transparent;">
                                    <div class="col num12"
                                        style="min-width:320px;max-width:600px;vertical-align:top;width:600px;">
                                        <div class="col_cont" style="width:100%;">
                                            <div align="center" class="img-container center autowidth"
                                                style="padding-right:20px;padding-left:20px;">
                                                <img border="0" class="center autowidth"
                                                    src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRGF00Oi-zJNU_EvYGueBVz_sqXmFjk8pxNtg&amp;usqp=CAU"
                                                    style="text-decoration:none;height:auto;border:0;max-width:541px;"
                                                    width="541"
                                                    alt="images?q=tbn:ANd9GcRGF00Oi-zJNU_EvYGueBVz_sqXmFjk8pxNtg&amp;usqp=CAU">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div style="background-color:#7c32ff;">
                            <div class="block-grid"
                                style="min-width:320px;max-width:600px;margin:0 auto;background-color:#ffffff;border-bottom-right-radius:30px;border-bottom-left-radius:30px;overflow:hidden;">
                                <div style="border-collapse:collapse;width:100%;background-color:#ffffff;">
                                    <div class="col num12"
                                        style="min-width:320px;max-width:600px;vertical-align:top;width:600px;">
                                        <div class="col_cont" style="width:100%;">
                                            <h1 style="line-height:120%;text-align:center;margin-bottom:0px;">
                                                <font color="#555555" face="Arial, Helvetica Neue, Helvetica, sans-serif">
                                                    <span style="font-size:36px;">Leave Applied</span>
                                                </font>
                                            </h1>
                                            <div style="line-height:1.8;padding:20px 15px;">
                                                <div class="txtTinyMce-wrapper" style="line-height:1.8;">
                                                    <h1>Dear Admin,</h1>
                                                    <p
                                                        style="margin:10px 0px 30px;line-height:1.929;font-size:16px;color:rgb(113,128,150);">
                                                        A [role] named [name] applied for a leave on [apply_date] from [leave_from] to [leave_to] for reason [reason].
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div style="background-color:#7c32ff;">
                            <div class="block-grid"
                                style="min-width:320px;max-width:600px;margin:0 auto;background-color:transparent;">
                                <div style="border-collapse:collapse;width:100%;background-color:transparent;">
                                    <div class="col num12"
                                        style="min-width:320px;max-width:600px;vertical-align:top;width:600px;">
                                        <div class="col_cont" style="width:100%;">
                                            <div
                                                style="color:#262b30;font-family:Arial, Helvetica Neue, Helvetica, sans-serif;line-height:1.2;padding-top:30px;padding-right:5px;padding-bottom:5px;padding-left:5px;">
                                                <div class="txtTinyMce-wrapper"
                                                    style="line-height:1.2;font-size:12px;font-family:Arial, Helvetica Neue, Helvetica, sans-serif;color:#262b30;">
                                                    <p
                                                        style="margin:0;font-size:12px;line-height:1.2;text-align:center;margin-top:0;margin-bottom:0;">
                                                        <span style="font-size:14px;color:rgb(255,255,255);font-family:Arial;">
                                                            © 2024 Infix Education software|
                                                        </span>
                                                        <span style="background-color:transparent;text-align:left;">
                                                            <font color="#ffffff">
                                                                Copyright &copy; 2024 All rights reserved | This application is
                                                                made by Codethemes
                                                            </font>
                                                        </span>
                                                        <br>
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>', '', '[name], [email], [role], [apply_date], [leave_from], [leave_to], [reason]'
            ],

            [
                'email', 'leave_notification', 'Leave Notification',
                '<table bgcolor="#FFFFFF" cellpadding="0" cellspacing="0" class="nl-container"
                style="table-layout:fixed;vertical-align:top;min-width:320px;border-spacing:0;border-collapse:collapse;background-color:#FFFFFF;width:100%;"
                width="100%">
                <tbody>
                    <tr style="vertical-align:top;" valign="top">
                        <td style="vertical-align:top;" valign="top">
                            <div style="background-color:#415094;">
                                <div class="block-grid"
                                    style="min-width:320px;max-width:600px;margin:0 auto;background-color:transparent;">
                                    <div
                                        style="border-collapse:collapse;width:100%;background-color:transparent;background-position:center top;background-repeat:no-repeat;">
                                        <div class="col num12"
                                            style="min-width:320px;max-width:600px;vertical-align:top;width:600px;">
                                            <div class="col_cont" style="width:100%;">
                                                <div align="center" class="img-container center fixedwidth"
                                                    style="padding-right:30px;padding-left:30px;">
                                                    <a href="#">
                                                        <img border="0" class="center fixedwidth" src=""
                                                            style="padding-top:30px;padding-bottom:30px;text-decoration:none;height:auto;border:0;max-width:150px;"
                                                            width="150" alt="logo.png">
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div style="background-color:#415094;">
                                <div class="block-grid"
                                    style="min-width:320px;max-width:600px;margin:0 auto;background-color:#ffffff;padding-top:25px;border-top-right-radius:30px;border-top-left-radius:30px;">
                                    <div style="border-collapse:collapse;width:100%;background-color:transparent;">
                                        <div class="col num12"
                                            style="min-width:320px;max-width:600px;vertical-align:top;width:600px;">
                                            <div class="col_cont" style="width:100%;">
                                                <div align="center" class="img-container center autowidth"
                                                    style="padding-right:20px;padding-left:20px;">
                                                    <img border="0" class="center autowidth"
                                                        src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRGF00Oi-zJNU_EvYGueBVz_sqXmFjk8pxNtg&amp;usqp=CAU"
                                                        style="text-decoration:none;height:auto;border:0;max-width:541px;"
                                                        width="541"
                                                        alt="images?q=tbn:ANd9GcRGF00Oi-zJNU_EvYGueBVz_sqXmFjk8pxNtg&amp;usqp=CAU">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div style="background-color:#7c32ff;">
                                <div class="block-grid"
                                    style="min-width:320px;max-width:600px;margin:0 auto;background-color:#ffffff;border-bottom-right-radius:30px;border-bottom-left-radius:30px;overflow:hidden;">
                                    <div style="border-collapse:collapse;width:100%;background-color:#ffffff;">
                                        <div class="col num12"
                                            style="min-width:320px;max-width:600px;vertical-align:top;width:600px;">
                                            <div class="col_cont" style="width:100%;">
                                                <h1 style="line-height:120%;text-align:center;margin-bottom:0px;">
                                                    <font color="#555555" face="Arial, Helvetica Neue, Helvetica, sans-serif">
                                                        <span style="font-size:36px;">Leave Notification</span>
                                                    </font>
                                                </h1>
                                                <div style="line-height:1.8;padding:20px 15px;">
                                                    <div class="txtTinyMce-wrapper" style="line-height:1.8;">
                                                        <h1>Dear [name],</h1>
                                                        <p
                                                            style="margin:10px 0px 30px;line-height:1.929;font-size:16px;color:rgb(113,128,150);">
                                                            Your application for leave on [apply_date] from [leave_from] to [leave_to] for reason [reason] is [status].
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div style="background-color:#7c32ff;">
                                <div class="block-grid"
                                    style="min-width:320px;max-width:600px;margin:0 auto;background-color:transparent;">
                                    <div style="border-collapse:collapse;width:100%;background-color:transparent;">
                                        <div class="col num12"
                                            style="min-width:320px;max-width:600px;vertical-align:top;width:600px;">
                                            <div class="col_cont" style="width:100%;">
                                                <div
                                                    style="color:#262b30;font-family:Arial, Helvetica Neue, Helvetica, sans-serif;line-height:1.2;padding-top:30px;padding-right:5px;padding-bottom:5px;padding-left:5px;">
                                                    <div class="txtTinyMce-wrapper"
                                                        style="line-height:1.2;font-size:12px;font-family:Arial, Helvetica Neue, Helvetica, sans-serif;color:#262b30;">
                                                        <p
                                                            style="margin:0;font-size:12px;line-height:1.2;text-align:center;margin-top:0;margin-bottom:0;">
                                                            <span style="font-size:14px;color:rgb(255,255,255);font-family:Arial;">
                                                                © 2024 Infix Education software|
                                                            </span>
                                                            <span style="background-color:transparent;text-align:left;">
                                                                <font color="#ffffff">
                                                                    Copyright &copy; 2024 All rights reserved | This application is
                                                                    made by Codethemes
                                                                </font>
                                                            </span>
                                                            <br>
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>', '', '[name], [email], [role], [apply_date], [leave_from], [leave_to], [reason], [status]'
            ],
        ];

        foreach ($emailTemplates as $allTemplete) {
            $storeTemplete = new SmsTemplate();
            $storeTemplete->type = $allTemplete[0];
            $storeTemplete->purpose = $allTemplete[1];
            $storeTemplete->subject = $allTemplete[2];
            $storeTemplete->body = $allTemplete[3];
            $storeTemplete->module = $allTemplete[4];
            $storeTemplete->variable = $allTemplete[5];
            $storeTemplete->save();
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('calendar_menus');
    }
};
