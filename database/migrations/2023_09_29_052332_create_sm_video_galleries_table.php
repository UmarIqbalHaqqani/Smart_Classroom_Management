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
        Schema::create('sm_video_galleries', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->text('description')->nullable();
            $table->text('video_link')->nullable();
            $table->boolean('is_publish')->default(true);
            $table->integer('position')->default(0);
            $table->integer('school_id')->nullable()->default(1)->unsigned();
            $table->foreign('school_id')->references('id')->on('sm_schools')->onDelete('cascade');
            $table->timestamps();
        });

        DB::table('sm_video_galleries')->insert([
            [
                'name' => "Science Fair",
                'description' => "A showcase of student experiments and scientific discoveries",
                'video_link' => "https://www.youtube.com/watch?v=4zR-uaZjZ2U",
                'position' => 1,
            ],
            [
                'name' => "Cultural Carnival",
                'description' => "A lively celebration of diverse traditions, arts, and festivities.",
                'video_link' => "https://www.youtube.com/watch?v=k61cLi1_Zd0&ab_channel=Infixdev",
                'position' => 2,
            ],
            [
                'name' => "Student Leadership Summit",
                'description' => "Empowering future leaders through collaboration and inspiration.",
                'video_link' => "https://www.youtube.com/watch?v=4zR-uaZjZ2U&ab_channel=Infixdev",
                'position' => 3,
            ],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sm_video_galleries');
    }
};
