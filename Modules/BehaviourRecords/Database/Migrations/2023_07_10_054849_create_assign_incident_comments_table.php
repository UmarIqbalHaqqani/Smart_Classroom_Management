<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAssignIncidentCommentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('assign_incident_comments', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id')->nullable();
            $table->longText('comment')->nullable();

            $table->integer('incident_id')->unsigned();

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
        Schema::dropIfExists('assign_incident_comments');
    }
}
