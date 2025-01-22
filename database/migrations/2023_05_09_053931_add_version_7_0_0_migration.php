<?php

use App\SmSchool;
use App\SmsTemplate;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddVersion700Migration extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('student_attendance_bulks', function (Blueprint $table) {
            if(!Schema::hasColumn('student_attendance_bulks', 'student_record_id')){
                $table->integer('student_record_id')->after('student_id')->nullable();
            }           
        });
        Schema::table('student_record_temporaries', function (Blueprint $table) {
            if(!Schema::hasColumn('student_record_temporaries', 'active_status')){
                $table->integer('active_status')->nullable();
            }           
        });
        Schema::table('exam_merit_positions', function (Blueprint $table) {
            if(Schema::hasColumn('exam_merit_positions', 'grade')){
                $table->string('grade')->nullable()->change();
            }           
        });
        Schema::table('sm_item_receives', function (Blueprint $table) {
            if(Schema::hasColumn('sm_item_receives', 'grand_total')){
                $table->float('grand_total')->nullable()->change();
            }           
        });
        Schema::table('sm_item_receives', function (Blueprint $table) {
            if(Schema::hasColumn('sm_item_receives', 'total_quantity')){
                $table->float('total_quantity')->nullable()->change();
            }           
        });
        Schema::table('sm_item_receives', function (Blueprint $table) {
            if(Schema::hasColumn('sm_item_receives', 'total_paid')){
                $table->float('total_paid')->nullable()->change();
            }           
        });
        Schema::table('sm_item_receives', function (Blueprint $table) {
            if(Schema::hasColumn('sm_item_receives', 'total_due')){
                $table->float('total_due')->nullable()->change();
            }           
        });
        Schema::table('sm_item_receive_children', function (Blueprint $table) {
            if(Schema::hasColumn('sm_item_receive_children', 'unit_price')){
                $table->float('unit_price')->nullable()->change();
            }           
        });
        Schema::table('sm_item_receive_children', function (Blueprint $table) {
            if(Schema::hasColumn('sm_item_receive_children', 'quantity')){
                $table->float('quantity')->nullable()->change();
            }           
        });
        Schema::table('sm_item_receive_children', function (Blueprint $table) {
            if(Schema::hasColumn('sm_item_receive_children', 'sub_total')){
                $table->float('sub_total')->nullable()->change();
            }           
        });
        Schema::table('custom_sms_settings', function (Blueprint $table) {
            if(!Schema::hasColumn('custom_sms_settings', 'school_id')){
                $table->integer('school_id')->default(1);
            }           
        });
        Schema::table('lesson_planners', function (Blueprint $table) {
            if(Schema::hasColumn('lesson_planners', 'topic_detail_id')){
                $table->integer('topic_detail_id')->nullable()->change();
            }           
        });
        Schema::table('sm_item_sells', function (Blueprint $table) {
            if(Schema::hasColumn('sm_item_sells', 'grand_total')){
                $table->float('grand_total')->nullable()->change();
            }           
        });
        Schema::table('sm_item_sells', function (Blueprint $table) {
            if(Schema::hasColumn('sm_item_sells', 'total_quantity')){
                $table->float('total_quantity')->nullable()->change();
            }           
        });
        Schema::table('sm_item_sells', function (Blueprint $table) {
            if(Schema::hasColumn('sm_item_sells', 'total_paid')){
                $table->float('total_paid')->nullable()->change();
            }           
        });
        Schema::table('sm_item_sells', function (Blueprint $table) {
            if(Schema::hasColumn('sm_item_sells', 'total_due')){
                $table->float('total_due')->nullable()->change();
            }           
        });
        Schema::table('infix_module_managers', function (Blueprint $table) {
            if(!Schema::hasColumn('infix_module_managers', 'lang_type')){
                $table->integer('lang_type')->nullable();
            }           
        });
        Schema::table('sm_exams', function (Blueprint $table) {
            if(!Schema::hasColumn('sm_exams', 'parent_id')){
                $table->integer('parent_id')->nullable()->default(0)->unsigned();
            }           
        });
        

        Schema::table('fm_fees_transactions', function (Blueprint $table) {
            if (!Schema::hasColumn('fm_fees_transactions', 'total_paid_amount')) {
                $table->string('total_paid_amount')->nullable();
            }
        });

        Schema::table('sm_class_sections', function (Blueprint $table) {
            if (!Schema::hasColumn('sm_class_sections', 'class_id')) {
                if (!Schema::hasColumn('sm_class_sections', 'section_id')) {
                    $table->index(['class_id', 'section_id']);
                }
            }
        });

        Schema::table('sm_exam_types', function (Blueprint $table) {
            if (!Schema::hasColumn('sm_exam_types', 'parent_id')) {
                $table->integer('parent_id')->nullable()->default(0)->unsigned();
            }
        });

        Schema::table('sm_student_certificates', function (Blueprint $table) {
            if (!Schema::hasColumn('sm_student_certificates', 'body_two')) {
                $table->text('body_two')->nullable();
            }
        });

        Schema::table('sm_student_certificates', function (Blueprint $table) {
            if (!Schema::hasColumn('sm_student_certificates', 'certificate_no')) {
                $table->text('certificate_no')->nullable();
            }
        });

        Schema::table('sm_student_certificates', function (Blueprint $table) {
            if (!Schema::hasColumn('sm_student_certificates', 'type')) {
                $table->string('type')->nullable()->default('school');
            }
        });

        $allTempletes = [
            ['sms', 'exam_mark_student', '', 'Hi [student_name] , You are in class [class_name] ([section_name]), Your exam type [exam_type], [subject_marks]. School Name- [school_name]', '', '[student_name], [class_name], [section_name], [exam_type], [subject_names], [total_mark], [school_name], [subject_marks]'],
            ['sms', 'exam_mark_parent', '', 'Hello, [parent_name], your child [student_name] of class [class_name] ([section_name]) exam type [exam_type], [subject_marks]. School Name- [school_name], Thank You.', '', '[parent_name], [student_name], [class_name], [section_name], [exam_type], [subject_names], [total_mark], [school_name], [subject_marks]'],
        ];

        $schools = SmSchool::get(['id', 'school_name']);
        foreach ($schools as $school) {
            foreach ($allTempletes as $allTemplete) {
                if (!SmsTemplate::where('purpose', $allTemplete[1])->first()) {
                    $storeTemplete = new SmsTemplate();
                    $storeTemplete->type = $allTemplete[0];
                    $storeTemplete->purpose = $allTemplete[1];
                    $storeTemplete->subject = $allTemplete[2];
                    $storeTemplete->body = $allTemplete[3];
                    $storeTemplete->module = $allTemplete[4];
                    $storeTemplete->variable = $allTemplete[5];
                    $storeTemplete->school_id = $school->id;
                    $storeTemplete->save();
                }
            }
        }

        $allDatas = SmsTemplate::all();
        foreach($allDatas as $allData){
            $existsData = str_contains($allData->variable, "[school_name]");
            $allData->variable = ($existsData) ? $allData->variable : $allData->variable.", [school_name]";
            $allData->save();
        }
        
        $templete = SmsTemplate::where('purpose', 'student_dues_fees')->first();
        $templete1 = SmsTemplate::where('purpose', 'student_dues_fees_for_parent')->first();

        $studentUpdate = SmsTemplate::find($templete->id);
        $studentUpdate->module = 'Fees';
        $studentUpdate->variable = '[student_name], [dues_amount], [fees_name], [date], [school_name]';
        $studentUpdate->save();

        $parentUpdate = SmsTemplate::find($templete1->id);
        $parentUpdate->module = 'Fees';
        $parentUpdate->variable = '[parent_name], [dues_amount], [fees_name], [date], [school_name]';
        $parentUpdate->save();

        $schools = SmSchool::get();
        
        foreach($schools as $school){
            $studenAttandance = SmsTemplate::where('purpose', 'parent_leave_approve_for_student')->where('school_id', $school->id)->first();
            $studenAttandance->body= str_replace('[staff_name]','[parent_name]',$studenAttandance->body);
            $studenAttandance->variable= str_replace('[staff_name]','[parent_name]',$studenAttandance->variable);
            $studenAttandance->save();

            $holiday = SmsTemplate::where('purpose', 'holiday')->where('school_id', $school->id)->first();
            $holiday->body= str_replace('[holiday_name]',' ',$holiday->body);
            $holiday->variable= str_replace('[holiday_name]',' ',$holiday->variable);
            $holiday->save();

            $BioMat1 = SmsTemplate::where('purpose', 'student_checkout')->where('school_id', $school->id)->first();
            $BioMat1->module= "InfixBiometrics";
            $BioMat1->save();

            $BioMat2 = SmsTemplate::where('purpose', 'student_early_checkout')->where('school_id', $school->id)->first();
            $BioMat2->module= "InfixBiometrics";
            $BioMat2->save();

            $check1 = SmsTemplate::where('purpose', 'student_fees_due')->where('school_id', $school->id)->first();
            if(!$check1){
                $storeFeesDueStudent = new SmsTemplate();
                $storeFeesDueStudent->type = "sms";
                $storeFeesDueStudent->purpose = "student_fees_due";
                $storeFeesDueStudent->subject = "";
                $storeFeesDueStudent->body = "Hi [student_name], You fees due amount [dues_amount] for [fees_name] on [date]. Thank You [school_name]";
                $storeFeesDueStudent->module = "";
                $storeFeesDueStudent->variable = "[student_name], [dues_amount], [fees_name], [date], [school_name]";
                $storeFeesDueStudent->status = 1;
                $storeFeesDueStudent->school_id = $school->id;
                $storeFeesDueStudent->save();
            }

            $check2 = SmsTemplate::where('purpose', 'student_fees_due_for_parent')->where('school_id', $school->id)->first();
            if(!$check2){
                $storeFeesDueStudent = new SmsTemplate();
                $storeFeesDueStudent->type = "sms";
                $storeFeesDueStudent->purpose = "student_fees_due_for_parent";
                $storeFeesDueStudent->subject = "";
                $storeFeesDueStudent->body = "Hi [parent_name], You fees due amount [dues_amount] for [fees_name] on [date]. Thank You [school_name]";
                $storeFeesDueStudent->module = "";
                $storeFeesDueStudent->variable = "[parent_name], [dues_amount], [fees_name], [date], [school_name]";
                $storeFeesDueStudent->status = 1;
                $storeFeesDueStudent->school_id = $school->id;
                $storeFeesDueStudent->save();
            }

            $check3 = SmsTemplate::where('purpose', 'due_fees_payment')->where('school_id', $school->id)->first();
            if(!$check3){
                $storeFeesDueStudent = new SmsTemplate();
                $storeFeesDueStudent->type = "email";
                $storeFeesDueStudent->purpose = "due_fees_payment";
                $storeFeesDueStudent->subject = "Duee Fees Payment";
                $storeFeesDueStudent->body = '
                                            <table bgcolor="#FFFFFF" cellpadding="0" cellspacing="0" class="nl-container" style="table-layout:fixed;vertical-align:top;min-width:320px;border-spacing:0;border-collapse:collapse;background-color:#FFFFFF;width:100%;" width="100%">
                                            <tbody>
                                                <tr style="vertical-align:top;" valign="top">
                                                    <td style="vertical-align:top;" valign="top">
                                                        <div style="background-color:#415094;">
                                                            <div class="block-grid" style="min-width:320px;max-width:600px;margin:0 auto;background-color:transparent;">
                                                                <div style="border-collapse:collapse;width:100%;background-color:transparent;background-position:center top;background-repeat:no-repeat;">
                                                                    <div class="col num12" style="min-width:320px;max-width:600px;vertical-align:top;width:600px;">
                                                                        <div class="col_cont" style="width:100%;">
                                                                            <div align="center" class="img-container center fixedwidth" style="padding-right:30px;padding-left:30px;">
                                                                                <a href="#">
                                                                                    <img border="0" class="center fixedwidth" src="" style="padding-top:30px;padding-bottom:30px;text-decoration:none;height:auto;border:0;max-width:150px;" width="150" alt="logo.png">
                                                                                </a>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div style="background-color:#415094;">
                                                            <div class="block-grid" style="min-width:320px;max-width:600px;margin:0 auto;background-color:#ffffff;padding-top:25px;border-top-right-radius:30px;border-top-left-radius:30px;">
                                                                <div style="border-collapse:collapse;width:100%;background-color:transparent;">
                                                                    <div class="col num12" style="min-width:320px;max-width:600px;vertical-align:top;width:600px;">
                                                                        <div class="col_cont" style="width:100%;">
                                                                            <div align="center" class="img-container center autowidth" style="padding-right:20px;padding-left:20px;">
                                                                                <img border="0" class="center autowidth" src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRGF00Oi-zJNU_EvYGueBVz_sqXmFjk8pxNtg&amp;usqp=CAU" style="text-decoration:none;height:auto;border:0;max-width:541px;" width="541" alt="images?q=tbn:ANd9GcRGF00Oi-zJNU_EvYGueBVz_sqXmFjk8pxNtg&amp;usqp=CAU">
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div style="background-color:#7c32ff;">
                                                            <div class="block-grid" style="min-width:320px;max-width:600px;margin:0 auto;background-color:#ffffff;border-bottom-right-radius:30px;border-bottom-left-radius:30px;overflow:hidden;">
                                                                <div style="border-collapse:collapse;width:100%;background-color:#ffffff;">
                                                                    <div class="col num12" style="min-width:320px;max-width:600px;vertical-align:top;width:600px;">
                                                                        <div class="col_cont" style="width:100%;">
                                                                            <h1 style="line-height:120%;text-align:center;margin-bottom:0px;">
                                                                                <font color="#555555" face="Arial, Helvetica Neue, Helvetica, sans-serif">
                                                                                    <span style="font-size:36px;">Dues Payment</span>
                                                                                </font>
                                                                            </h1>
                                                                            <div style="line-height:1.8;padding:20px 15px;">
                                                                                <div class="txtTinyMce-wrapper" style="line-height:1.8;">
                                                                                    <h1>Hi [student_name],</h1>
                                                                                    <p style="margin:10px 0px 30px;line-height:1.929;font-size:16px;color:rgb(113,128,150);">
                                                                                        You fees due amount [due_amount] for [fees_name] on [date]. Thank You, [school_name]
                                                                                    </p>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div style="background-color:#7c32ff;">
                                                            <div class="block-grid" style="min-width:320px;max-width:600px;margin:0 auto;background-color:transparent;">
                                                                <div style="border-collapse:collapse;width:100%;background-color:transparent;">
                                                                    <div class="col num12" style="min-width:320px;max-width:600px;vertical-align:top;width:600px;">
                                                                        <div class="col_cont" style="width:100%;">
                                                                            <div style="color:#262b30;font-family:Arial, Helvetica Neue, Helvetica, sans-serif;line-height:1.2;padding-top:30px;padding-right:5px;padding-bottom:5px;padding-left:5px;">
                                                                                <div class="txtTinyMce-wrapper" style="line-height:1.2;font-size:12px;font-family:Arial, Helvetica Neue, Helvetica, sans-serif;color:#262b30;">
                                                                                    <p style="margin:0;font-size:12px;line-height:1.2;text-align:center;margin-top:0;margin-bottom:0;">
                                                                                        <span style="font-size:14px;color:rgb(255,255,255);font-family:Arial;">
                                                                                            © 2024 Infix Education software|
                                                                                        </span>
                                                                                        <span style="background-color:transparent;text-align:left;">
                                                                                            <font color="#ffffff">
                                                                                                Copyright &copy; 2024 All rights reserved | This application is made by Codethemes
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
                                            </table>
                                            ';
                $storeFeesDueStudent->module = "";
                $storeFeesDueStudent->variable = "[student_name], [due_amount], [fees_name], [date], [school_name]";
                $storeFeesDueStudent->status = 1;
                $storeFeesDueStudent->school_id = $school->id;
                $storeFeesDueStudent->save();
            }
        }
    }

    public function down()
    {
        //
    }
}
