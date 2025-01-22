<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateContentShareListsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('content_share_lists', function (Blueprint $table) {
            $table->id();
            $table->string('title')->nullable();
            $table->date('share_date')->nullable();
            $table->date('valid_upto')->nullable();
            $table->text('description')->nullable();
            $table->string('send_type')->nullable()->comment('G, C, I, P');
            $table->json('content_ids')->nullable();
            $table->json('gr_role_ids')->nullable();
            $table->json('ind_user_ids')->nullable();
            $table->integer('class_id')->nullable();
            $table->json('section_ids')->nullable();
            $table->text('url')->nullable();
            $table->integer('shared_by')->nullable();
            $table->timestamps();

            $table->integer('academic_id')->nullable()->unsigned();
            $table->foreign('academic_id')->references('id')->on('sm_academic_years')->onDelete('cascade');

            $table->integer('school_id')->nullable()->default(1)->unsigned();
            $table->foreign('school_id')->references('id')->on('sm_schools')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('content_share_lists');
    }
}
