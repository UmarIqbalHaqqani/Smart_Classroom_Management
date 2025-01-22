<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(config('pagebuilder.db_prefix','infixedu__') . 'pages', function (Blueprint $table) {
            $table->id();
            $table->string('name')->fullText();
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('slug')->nullable();
            $table->longText('settings')->nullable();
            $table->boolean('home_page')->default(false)->nullable();
            $table->boolean('is_default')->default(false)->nullable();
            $table->enum('status', ['draft', 'published'])->default('draft')->index();
            $table->integer('created_by')->nullable();
            $table->integer('updated_by')->nullable();
            $table->integer('published_by')->nullable();
            $table->integer('school_id')->unsigned();
            $table->foreign('school_id')->references('id')->on('sm_schools')->onDelete('cascade');
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
        Schema::dropIfExists(config('pagebuilder.db_prefix') . 'pages');
    }
}
