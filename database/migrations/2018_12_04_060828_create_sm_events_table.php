<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSmEventsTable extends Migration
{
    public function up()
    {
        Schema::create('sm_events', function (Blueprint $table) {
            $table->increments('id');
            $table->string('event_title', 200)->nullable();
            $table->string('for_whom', 200)->nullable()->comment('teacher, student, parents, all');
            $table->text('role_ids')->nullable();
            $table->text('url')->nullable();
            $table->string('event_location', 200)->nullable();
            $table->string('event_des', 500)->nullable();
            $table->date('from_date')->nullable();
            $table->date('to_date')->nullable();
            $table->string('uplad_image_file', 200)->nullable();
            $table->tinyInteger('active_status')->default(1);
            $table->timestamps();

            $table->integer('created_by')->nullable()->default(1)->unsigned();
            $table->integer('updated_by')->nullable()->default(1)->unsigned();

            $table->integer('school_id')->nullable()->default(1)->unsigned();
            $table->foreign('school_id')->references('id')->on('sm_schools')->onDelete('cascade');

            $table->integer('academic_id')->nullable()->default(1)->unsigned();
            $table->foreign('academic_id')->references('id')->on('sm_academic_years')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sm_events');
    }
}
