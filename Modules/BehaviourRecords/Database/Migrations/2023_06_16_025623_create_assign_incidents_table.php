<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAssignIncidentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('assign_incidents', function (Blueprint $table) {
            $table->id();
            $table->integer('point')->nullable();

            $table->integer('incident_id')->unsigned();

            $table->integer('record_id')->unsigned();
            
            $table->integer('student_id')->nullable()->unsigned();
            
            $table->integer('added_by')->unsigned();

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
        Schema::dropIfExists('assign_incidents');
    }
}
