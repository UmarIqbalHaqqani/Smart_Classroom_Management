<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Faker\Factory as Faker;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('speech_sliders', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('designation')->nullable();
            $table->string('title')->nullable();
            $table->text('speech')->nullable();
            $table->string('image')->nullable();
            $table->integer('school_id')->nullable()->default(1)->unsigned();
            $table->foreign('school_id')->references('id')->on('sm_schools')->onDelete('cascade');
            $table->timestamps();
        });

        $faker = Faker::create();

        $datas = [
            "Principal's Speech",
            "Vice Principal's Speech",
            "Founder's Speech"
        ];
        foreach($datas as $key => $data){
            $key++;
            DB::table('speech_sliders')->insert([
                [
                    'name' => $faker->name,
                    'title' => "Speech From $data",
                    'designation' => $data,
                    'speech' => "Infix Edu is a traditional and reputed school, the students use their talents to develop creative spirit in creating skilled citizens, and the light of education has shown people the way of life.",
                    'image' => "public/uploads/theme/edulia/speech_slider/speech-$key.jpg",
                    'school_id' => 1,
                ],
            ]);
        }
        
    }

    public function down(): void
    {
        Schema::dropIfExists('speech_sliders');
    }
};
