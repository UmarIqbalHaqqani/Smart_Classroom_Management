<?php

use App\SmSchool;
use App\Models\FrontendExamResult;
use Illuminate\Support\Facades\DB;
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
        Schema::create('frontend_exam_results', function (Blueprint $table) {
            $table->id();
            $table->string('title')->nullable();
            $table->text('description')->nullable();
            $table->string('main_title')->nullable();
            $table->text('main_description')->nullable();
            $table->string('image')->nullable();
            $table->string('main_image')->nullable();
            $table->string('button_text')->nullable();
            $table->string('button_url')->nullable();
            $table->tinyInteger('active_status')->default(1);

            $table->integer('created_by')->nullable()->default(1)->unsigned();

            $table->integer('updated_by')->nullable()->default(1)->unsigned();

            $table->integer('school_id')->nullable()->default(1)->unsigned();
            $table->foreign('school_id')->references('id')->on('sm_schools')->onDelete('cascade');
            $table->timestamps();
        });
        $schools = SmSchool::all();
        foreach($schools as $school){
            $new = new FrontendExamResult();
            $new->title = 'Exam Result';
            $new->description = 'Lisus consequat sapien metus dis urna, facilisi. Nonummy rutrum eu lacinia platea a, ipsum parturient, orci tristique. Nisi diam natoque.';
            $new->image = 'public/uploads/about_page/about.jpg';
            $new->button_text = 'Learn More Exam';
            $new->button_url = 'exam-result';
            $new->main_title='Under Graduate Education';
            $new->main_description='INFIX has all in one place. You’ll find everything what you are looking into education management system software. We care! User will never bothered in our real eye catchy user friendly UI & UX  Interface design. You know! Smart Idea always comes to well planners. And Our INFIX is Smart for its Well Documentation. Explore in new support world! It’s now faster & quicker. You’ll find us on Support Ticket, Email, Skype, WhatsApp.';
            $new->main_image ='public/uploads/about_page/about-img.jpg';
            $new->school_id = $school->id;
            $new->save();
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('frontend_exam_results');
    }
};
