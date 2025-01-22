<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTeacherEvaluationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('teacher_evaluations', function (Blueprint $table) {
            $table->id();
            
            $table->text('rating')->nullable();

            $table->string('comment')->nullable();

            $table->boolean('status')->nullable()->default(false);

            $table->integer('record_id')->unsigned();
            
            $table->integer('subject_id')->nullable()->unsigned();
            
            $table->integer('teacher_id')->nullable()->unsigned();
            
            $table->integer('student_id')->nullable()->unsigned();
            
            $table->integer('role_id')->nullable()->unsigned();
            
            $table->integer('parent_id')->nullable()->unsigned();

            $table->integer('academic_id')->nullable()->unsigned();
            
            $table->integer('school_id')->default(1)->unsigned();

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
        Schema::dropIfExists('teacher_evaluations');
    }
}
