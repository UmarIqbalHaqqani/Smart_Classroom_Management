<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTeacherEvaluationSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('teacher_evaluation_settings', function (Blueprint $table) {
            $table->id();

            $table->boolean('is_enable')->default(0);

            $table->string('submitted_by')->default('[]');

            $table->string('rating_submission_time')->default('any');

            $table->boolean('auto_approval')->default(1);

            $table->date('from_date')->nullable();

            $table->date('to_date')->nullable();

            $table->integer('school_id')->default(1)->unsigned();

            $table->timestamps();
        });

        DB::table('teacher_evaluation_settings')->insert([
            [
                'is_enable' => 0,
            ],
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('teacher_evaluation_settings');
    }
}
