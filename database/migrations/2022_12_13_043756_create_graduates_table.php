<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGraduatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('graduates', function (Blueprint $table) {
            $table->id();
            $table->integer('record_id')->nullable()->unsigned();
            $table->integer('student_id')->nullable()->unsigned();
            $table->foreign('student_id')->references('id')->on('sm_students')->onDelete('cascade');
            $table->integer('created_by')->nullable()->unsigned();
            $table->integer('un_department_id')->nullable();
            $table->integer('un_faculty_id')->nullable();
            $table->integer('graduation_date')->nullable();
            $table->integer('un_session_id')->nullable()->default(1)->unsigned();
            $table->integer('school_id')->default(1)->unsigned();
            $table->foreign('school_id')->references('id')->on('sm_schools')->onDelete('cascade');
            $table->integer('session_id')->nullable()->unsigned();
            $table->foreign('session_id')->references('id')->on('sm_sessions')->onDelete('cascade');
            $table->integer('class_id')->nullable()->unsigned();
            $table->foreign('class_id')->references('id')->on('sm_classes')->onDelete('cascade');
            $table->integer('section_id')->nullable()->unsigned();
            $table->foreign('section_id')->references('id')->on('sm_sections')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('graduates');
    }
}
