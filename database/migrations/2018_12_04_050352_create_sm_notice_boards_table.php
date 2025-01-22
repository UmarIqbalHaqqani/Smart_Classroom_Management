<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSmNoticeBoardsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sm_notice_boards', function (Blueprint $table) {
            $table->increments('id');
            $table->string('notice_title', 200)->nullable();
            $table->text('notice_message')->nullable();
            $table->date('notice_date')->nullable();
            $table->date('publish_on')->nullable();
            $table->string('inform_to', 200)->nullable()->comment('Notice message sent to these roles');
            $table->tinyInteger('active_status')->default(1);
            $table->integer('is_published')->nullable()->default(0);
            $table->timestamps();


            $table->integer('created_by')->nullable()->default(1)->unsigned();

            $table->integer('updated_by')->nullable()->default(1)->unsigned();

            $table->integer('school_id')->nullable()->default(1)->unsigned();
            $table->foreign('school_id')->references('id')->on('sm_schools')->onDelete('cascade');
            
            $table->integer('academic_id')->nullable()->default(1)->unsigned();
            $table->foreign('academic_id')->references('id')->on('sm_academic_years')->onDelete('cascade');
        });

        DB::table('sm_notice_boards')->insert([
            [
                'notice_title' => 'This is a sample notice 1',
                'notice_message' => 'This a demo notice',
                'notice_date' => date("Y-m-d"),
                'publish_on' => date("Y-m-d"),
                'inform_to' => "[1]",
                'is_published' => 1,
            ],
            [
                'notice_title' => 'This is another sample notice 2',
                'notice_message' => 'This a demo notice',
                'notice_date' => date("Y-m-d"),
                'publish_on' => date("Y-m-d"),
                'inform_to' => "[1]",
                'is_published' => 1,
            ],
            [
                'notice_title' => 'This is another sample notice 3',
                'notice_message' => 'This a demo notice',
                'notice_date' => date("Y-m-d"),
                'publish_on' => date("Y-m-d"),
                'inform_to' => "[1]",
                'is_published' => 1,
            ],
            [
                'notice_title' => 'This is another sample notice 4',
                'notice_message' => 'This a demo notice',
                'notice_date' => date("Y-m-d"),
                'publish_on' => date("Y-m-d"),
                'inform_to' => "[1]",
                'is_published' => 1,
            ],
            [
                'notice_title' => 'This is another sample notice 5',
                'notice_message' => 'This a demo notice',
                'notice_date' => date("Y-m-d"),
                'publish_on' => date("Y-m-d"),
                'inform_to' => "[1]",
                'is_published' => 1,
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
        Schema::dropIfExists('sm_notice_boards');
    }
}
