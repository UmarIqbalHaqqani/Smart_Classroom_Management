<?php

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
        Schema::create('sm_form_downloads', function (Blueprint $table) {
            $table->id();
            $table->string('title')->nullable();
            $table->string('short_description', 200)->nullable();
            $table->date('publish_date')->nullable();
            $table->string('link')->nullable();
            $table->string('file')->nullable();
            $table->tinyInteger('show_public')->default(1);
            $table->timestamps();

            $table->integer('school_id')->nullable()->default(1)->unsigned();
            $table->foreign('school_id')->references('id')->on('sm_schools')->onDelete('cascade');
        });

        $datas = [
            ['Exam Routine', 'Exam Routine',],
            ['Class Routine', 'Class Routine'],
            ['Open An Bank Account Routine', 'Open An Bank Account Routine'],
        ];
        foreach($datas as $key => $data){
            $key++;
            DB::table('sm_form_downloads')->insert([
                'title' => $data[0],
                'short_description' => $data[1],
                'publish_date' => date("Y-m-d"),
                'file' => "public/uploads/theme/edulia/form_download/file-$key.pdf",
                'school_id' => 1,
            ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sm_form_downloads');
    }
};
