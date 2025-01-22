<?php

use App\SmsTemplate;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBehaviourRecordSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('behaviour_record_settings', function (Blueprint $table) {
            $table->id();
            $table->integer('student_comment')->nullable();
            $table->integer('parent_comment')->nullable();
            $table->integer('student_view')->nullable();
            $table->integer('parent_view')->nullable();

            $table->integer('school_id')->default(1)->unsigned();

            $table->timestamps();
        });

        DB::table('behaviour_record_settings')->insert([
            [
                'student_comment' => 0,
                'parent_comment' => 0,
                'student_view' => 0,
                'parent_view' => 0,
            ],
        ]);

        $allTempletes = [
            // SMS Start
            ['sms', 'behaviour_record_update', 'Behaviour Record Update', 'Greetings, A new Incident: [incident] with [point] point has been assigned to Student: [student_name], Class: [class], Section: [section] by [assigned_ny] according to his/her academic behaviour. His current total behaviour point is [total_point]. Visit your own panel for more details or contact with admin if you face any issues. Thank you [school_name].', 'BehaviourRecords', '[student_name], [class], [section], [assigned_ny], [school_name], [incident], [point], [parent_name], [total_point], [role]'],
            [
                'email', 'behaviour_record_update', 'Behaviour Record Update',
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
                                                <font face="Arial, Helvetica Neue, Helvetica, sans-serif"><span
                                                        style="font-size: 36px;"><br></span></font>
                                            </h1>
                                            <h1 style="line-height:120%;text-align:center;margin-bottom:0px;">
                                                <font face="Arial, Helvetica Neue, Helvetica, sans-serif"><span
                                                        style="font-size: 36px;"><u>Behaviour Record update for
                                                        [student_name]</u></span></font>
                                                <br>
                                            </h1>
                                            <div style="line-height:1.8;padding:20px 15px;">
                                                <div class="txtTinyMce-wrapper" style="line-height:1.8;">
                                                    <h1>Greetings,</h1>
                                                    <p
                                                        style="margin:10px 0px 30px;line-height:1.929;font-size:16px;color:rgb(113,128,150);">
                                                        A new Incident: [incident] with [point] point has been assigned to
                                                        Student: [student_name], Class: [class], Section: [section] by
                                                        [assigned_ny] according to his/her academic behaviour. His current total
                                                        behaviour point is [total_point]. Visit your own panel for more details
                                                        or contact with admin if you face any issues.</p>
                                                    <br>
                                                    Thank You, [school_name]
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
                                                            © 2024 Infix Education software|&nbsp;
                                                        </span>
                                                        <span style="background-color:transparent;text-align:left;">
                                                            <font color="#ffffff">
                                                                Copyright © 2024 All rights reserved | This application is made
                                                                by Codethemes
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
        </table>', 'BehaviourRecords', '[student_name], [class], [section], [assigned_ny], [school_name], [incident], [point], [parent_name], [total_point], [role]'
            ],
        ];

        foreach ($allTempletes as $allTemplete) {
            $exist = SmsTemplate::where('purpose', $allTemplete[1])->where('type', $allTemplete[0])->first();
            if (!$exist) {
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
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('behaviour_record_settings');
    }
}
