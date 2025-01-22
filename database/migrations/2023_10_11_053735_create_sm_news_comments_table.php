<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::create('sm_news_comments', function (Blueprint $table) {
            $table->id();
            $table->text('message');

            $table->integer('news_id')->nullable()->unsigned();
            $table->foreign('news_id')->references('id')->on('sm_news')->onDelete('cascade');

            $table->integer('user_id') ->nullable()->unsigned();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

            $table->integer('parent_id')->nullable();
            $table->tinyInteger('status')->default(0)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sm_news_comments');
    }
};
